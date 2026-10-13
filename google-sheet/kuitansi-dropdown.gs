/**
 * Dropdown nama karyawan/pensiunan + keluarga untuk spreadsheet Monitoring
 * Kuitansi Kesehatan. Daftar nama diambil langsung dari Master Data aplikasi
 * Surat SDM, jadi ejaannya selalu sama dengan data di aplikasi.
 *
 * Pemasangan (sekali saja):
 *   1. Di spreadsheet: Extensions > Apps Script, hapus isi Code.gs, tempel
 *      seluruh file ini, lalu isi SHEET_MASTER_KEY di bawah.
 *   2. Pilih fungsi "setup" di toolbar, klik Run, lalu izinkan aksesnya.
 *   Selesai. Fungsi onEdit berjalan otomatis setiap ada yang mengetik, dan
 *   daftar nama diperbarui otomatis tiap jam (fungsi refreshMaster).
 *   Kalau ada karyawan baru dan ingin langsung muncul, jalankan refreshMaster.
 */

const API_URL = 'https://suratsdm-api-3aee8fd4-19b1-4fa5-9a2f-66e65ed7df35.fly.dev/api/sheet-master';
const SHEET_MASTER_KEY = 'ISI_KEY_DI_SINI';

// Tab input -> status karyawan di aplikasi.
const INPUT_TABS = {
  'KARYAWAN': 'karyawan_tetap',
  'KARYAWAN 2026': 'karyawan_tetap',
  'PENSIUNAN': 'pensiunan',
  'PENSIUNAN 2026': 'pensiunan',
};
const MASTER_TABS = {
  karyawan_tetap: 'MASTER_KARYAWAN',
  pensiunan: 'MASTER_PENSIUNAN',
};
const HEADER_ROW = 5;

// true = nama pasien di luar daftar keluarga ditolak.
// false = tetap boleh, hanya diberi tanda peringatan (mis. anak baru yang
// belum ditambahkan ke Master Data).
const STRICT_PASIEN = false;

function setup() {
  const ss = SpreadsheetApp.getActive();

  // Daftar diambil oleh script sendiri (bukan =IMPORTDATA), karena Sheets
  // memblokir IMPORTDATA sampai ada yang klik "Allow access" — dan tombol
  // itu tidak pernah muncul di tab yang disembunyikan.
  refreshMaster();

  // Perbarui daftar otomatis tiap jam.
  ScriptApp.getProjectTriggers()
    .filter(function (t) { return t.getHandlerFunction() === 'refreshMaster'; })
    .forEach(function (t) { ScriptApp.deleteTrigger(t); });
  ScriptApp.newTrigger('refreshMaster').timeBased().everyHours(1).create();

  Object.keys(INPUT_TABS).forEach(function (tabName) {
    const sheet = ss.getSheetByName(tabName);
    if (!sheet) return;
    const cols = findColumns_(sheet);
    if (!cols.nama) return;

    const master = ss.getSheetByName(MASTER_TABS[INPUT_TABS[tabName]]);
    // Mulai dari baris kosong pertama, supaya riwayat lama tidak ditandai.
    const startRow = lastFilledRow_(sheet, cols.nama) + 1;
    const numRows = sheet.getMaxRows() - startRow + 1;
    if (numRows < 1) return;

    const rule = SpreadsheetApp.newDataValidation()
      .requireValueInRange(master.getRange('A2:A'), true)
      .setAllowInvalid(false)
      .setHelpText('Pilih nama dari daftar (sesuai Master Data aplikasi Surat SDM).')
      .build();
    sheet.getRange(startRow, cols.nama, numRows, 1).setDataValidation(rule);
  });

  SpreadsheetApp.getUi().alert('Dropdown sudah dipasang.');
}

function refreshMaster() {
  const ss = SpreadsheetApp.getActive();
  Object.keys(MASTER_TABS).forEach(function (status) {
    const url = API_URL + '?status=' + status + '&key=' + encodeURIComponent(SHEET_MASTER_KEY);
    const res = UrlFetchApp.fetch(url, { muteHttpExceptions: true });
    if (res.getResponseCode() !== 200) {
      throw new Error('Gagal mengambil daftar ' + status + ' (HTTP ' + res.getResponseCode() + '). Cek SHEET_MASTER_KEY.');
    }
    const rows = Utilities.parseCsv(res.getContentText('UTF-8'));
    if (rows.length < 2) return; // jangan kosongkan daftar kalau respons kosong

    const name = MASTER_TABS[status];
    const sheet = ss.getSheetByName(name) || ss.insertSheet(name);
    sheet.clearContents();
    sheet.getRange(1, 1, rows.length, rows[0].length).setNumberFormat('@').setValues(rows);
    if (!sheet.isSheetHidden()) sheet.hideSheet();
  });
}

function onEdit(e) {
  const sheet = e.range.getSheet();
  const status = INPUT_TABS[sheet.getName()];
  if (!status) return;

  const cols = findColumns_(sheet);
  if (!cols.nama || !cols.pasien) return;

  const firstRow = Math.max(e.range.getRow(), HEADER_ROW + 1);
  const lastRow = e.range.getLastRow();
  const firstCol = e.range.getColumn();
  const lastCol = e.range.getLastColumn();
  const touches = function (col) { return col && col >= firstCol && col <= lastCol; };
  if (lastRow < firstRow || !(touches(cols.nama) || touches(cols.pasien))) return;

  const families = loadFamilies_(status);
  const selfLabel = status === 'pensiunan' ? 'PENSIUNAN' : 'KARYAWAN';

  for (let row = firstRow; row <= lastRow; row++) {
    const nama = String(sheet.getRange(row, cols.nama).getValue()).trim();
    const pasienCell = sheet.getRange(row, cols.pasien);

    if (touches(cols.nama)) {
      const family = families[nama.toUpperCase()];
      if (family) {
        pasienCell.setDataValidation(
          SpreadsheetApp.newDataValidation()
            .requireValueInList(family, true)
            .setAllowInvalid(!STRICT_PASIEN)
            .setHelpText('Pilih pasien: ybs atau keluarganya.')
            .build()
        );
      } else {
        pasienCell.clearDataValidations();
      }
    }

    // Tab 2026 punya kolom KARYAWAN/KELUARGA (atau PENSIUNAN/KELUARGA).
    if (cols.jenis) {
      const pasien = String(pasienCell.getValue()).trim();
      if (nama && pasien) {
        sheet.getRange(row, cols.jenis)
          .setValue(pasien.toUpperCase() === nama.toUpperCase() ? selfLabel : 'KELUARGA');
      }
    }
  }
}

function findColumns_(sheet) {
  const headers = sheet.getRange(HEADER_ROW, 1, 1, sheet.getLastColumn()).getValues()[0]
    .map(function (h) { return String(h).replace(/\s+/g, ' ').trim().toUpperCase(); });
  const col = function (names) {
    for (let i = 0; i < headers.length; i++) {
      if (names.indexOf(headers[i]) !== -1) return i + 1;
    }
    return 0;
  };
  return {
    nama: col(['NAMA KARYAWAN', 'NAMA PENSIUNAN']),
    pasien: col(['NAMA PASIEN']),
    jenis: col(['KARYAWAN/KELUARGA', 'PENSIUNAN/KELUARGA']),
  };
}

function lastFilledRow_(sheet, col) {
  const values = sheet.getRange(1, col, sheet.getLastRow(), 1).getValues();
  for (let i = values.length - 1; i >= 0; i--) {
    if (String(values[i][0]).trim() !== '') return Math.max(i + 1, HEADER_ROW);
  }
  return HEADER_ROW;
}

// Kolom MASTER: NAMA | NID | ISTRI/SUAMI | ANAK 1 | ANAK 2 | ANAK 3
function loadFamilies_(status) {
  const master = SpreadsheetApp.getActive().getSheetByName(MASTER_TABS[status]);
  const families = {};
  if (!master) return families;
  master.getDataRange().getValues().slice(1).forEach(function (r) {
    const nama = String(r[0]).trim();
    if (!nama) return;
    const members = [nama, r[2], r[3], r[4], r[5]]
      .map(function (v) { return String(v).trim(); })
      .filter(function (v, i, arr) { return v !== '' && arr.indexOf(v) === i; });
    families[nama.toUpperCase()] = members;
  });
  return families;
}
