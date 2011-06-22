var xhr = false;

function update_kota() {
	if (window.XMLHttpRequest) { // Jika browser mengenal XMLHttpRequest maka
		xhr = new XMLHttpRequest(); // Buat objek baru dengan nama xhr (XmlHttpRequest) 
	}
	else { // Jika browser tidak mengenal XMLHttpRequest
		if (window.ActiveXObject) {  // Jika browser mengenal ActiveXObject (Biasanya IE6)
			try {
				xhr = new ActiveXObject("Microsoft.XMLHTTP"); // Buat objct dengan nama xhr 
			}
			catch (e) { }
		}
	}

	if (xhr) {
		xhr.onreadystatechange = buatisiselect; // Isi fungsi yang akan dipanggil ketika ada perubahan status XMLHttpRequest
		xhr.open("GET", "lib_func/getkabkota.php?prov="+document.getElementById("provinsi").value, true); // Buka file yang ada di namafile di server
		xhr.send(null); // Lakukan request
	}
	else { // Jika objek XMLRequest tidak bisa dibuat
		document.getElementById("isi").innerHTML = "Maaf, Browser anda tidak mendukung AJAX";
	}
}

function buatisiselect() {
	if (xhr.readyState == 4) { // Jika readystatus request telah lengkap (4)
		if (xhr.status == 200) { // Jika status request OK (200)
			var response_server = xhr.responseText; // Ambil responseText
			var arr_kota=response_server.split('|'); // Split response dengan tanda | simpan diarray arr_kota
			var i;
			var select_kota=document.getElementById("kota"); // Ambil objek kota
			var data_kota; // Data isi sebuah kab/kota yang belum displit
			//Hapus isi yang ada di select Kab Kota
			while(select_kota.length>1){ // Selama masih ada pilihan di kota, 
				select_kota.remove(1); // hapus option pada posisi 2
			}			
			for(i=0;i<arr_kota.length-1;i++){ // Lakukan perulangan untuk semua baris yang ada di array arr_kota
					data_kota=arr_kota[i].split(";"); // Pecah berdasarkan ; untuk mengambil id dan nama kota
					var option=document.createElement("option"); // buat sebuah option
					option.value=data_kota[0];// value dari option baru diisi dengan id_kota
					option.text=data_kota[1]; // text pilihan dari option baru diisi dengan nama kota
					try{
						 select_kota.add(option,null); // Tambahkan option baru ke object select kota
					}catch(e) {
						select_kota.add(option);// khusus IE
					}
			}
		}
		else {
			document.getElementById("isi").innerHTML="Ada masalah dalam request dengan kode " + xhr.status + "("+xhr.statusText+")";
		}
	}
}
