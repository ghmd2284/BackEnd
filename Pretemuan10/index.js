/**
 * Fungsi untuk menampilkan hasil download
 * @param {string} result - Nama file yang didownload
 */
function showDownload(result) {
    console.log("Selesai");
    console.log("Download");
    console.log("Hasil Download: " + result);
   }
   
   /**
    * Fungsi untuk download file
    * @returns {Promise<string>} Promise yang mengembalikan nama file yang didownload
    */
   async function download() {
    return new Promise((resolve) => {
       setTimeout(() => {
         const result = "windows-10.exe";
         resolve(result);
       }, 3000);
    });
   }
   
   download().then(showDownload);