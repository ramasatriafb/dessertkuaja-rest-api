<?php 
if (user diabet  = 'yes')
{
//rule ldl
	if (ldl user <100){ normal}
		else if ldl user > 99 && ldl user <160
		{ hati hati }
	else {bahaya}
//rule trigliserida	
	if (tri  < 150) {normal}
		else if (tri <199 && tri > 401) {hati hati}
		else {bahaya}
//rule guldar	
	if (tri user > 199 && tri user < 399){hatihati}
	else {bahaya}
}

else {
//rule ldl
	if (ldl user < 130){normal}
	else if (ldl user >129 && ldl user <160){hatihati}
	else {bahaya}
//rule trigliserida
	if (tri user < 200) {normal}
	
//rule guldar
	if ( guldar < 100) {normal}
}

//cek rule hdl 
if (hdl > 60) {normal}
else if (hdl > 39 && hdl < 60){hati hati}
else {bahaya}

//rule asam urat
switch case (umur)
	case 1 umur < 18 then 
		if ( jk user = 'pria'){
			if (asamurat user < 3,5) {Rendah}
		else if (asamurat user > 3,4 && asamurat user < 5,6) {normal}
		else {tinggi}
		}else{
		if (asamurat user < 3,6) {Rendah}
		else if (asamurat user > 3,6 && asamurat user < 4,1) {normal}
		else {tinggi}
		}
	case 2 umur > 17 && umur <41 then
		if ( jk user = 'pria'){
			if (asamurat user < 2) {Rendah}
		else if (asamurat user > 1.9 && asamurat user < 7,6) {normal}
		else {tinggi}
		}else{
		if (asamurat user < 1,9) {Rendah}
		else if (asamurat user > 1,9 && asamurat user < 6,6) {normal}
		else {tinggi}
		}
	case 3 umur > 40 
		if ( jk user = 'pria'){
			if (asamurat user < 2) {Rendah}
		else if (asamurat user > 1.9 && asamurat user < 8,6) {normal}
		else {tinggi}
		}else{
		if (asamurat user < 1,9) {Rendah}
		else if (asamurat user > 1,9 && asamurat user < 8,1) {normal}
		else {tinggi}
		}


GULA DARAH = - RENDAH , NORMAL, TINGGI
ASAM URAT = - RENDAH, NORMAL, TINGGI
LDL = -  NORMAL, HATIHATI, TINGGI
HDL =  -  NORMAL, HATIHATI, TINGGI
TRIGLISERIDA = - NORMAL, HATIHATI, TINGGI

KATEGORI REKOMENDASI = 
GULDAR RENDAH
GULDAR NORMAL
GULDAR TINGGI
ASAM URAT RENDAH
ASAM URAT NORMAL
ASAM URAT TINGGI
LDL NORMAL
LDL HATIHATI
LDL BAHAYA
LDL NORMAL
LDL HATIHATI
LDL BAHAYA
LDL NORMAL
LDL HATIHATI
LDL BAHAYA

kasus hasil 
()() - normal = 
if hsl = normal {$kat = 'GULDAR NORMAL'}
else if (hsl = rendah_ {$kat = 'GULDAR_RENDAH'}
ELSE {$kat = 'GULDAR_TINGGI'}
SELECT A.NAMA_MAKANAN,A.FOTO,A.LIST_BAHAN,A.CARA_MEMASAK,A.FAKTA_NUTRISI FROM RESEP A
JOIN RESEP_REKOMENDASI C 
ON  C.ID = A.ID
JOIN KATEGORI_REKOMENDASI B
ON C.ID = B.ID
WHERE B.NAMA_REKOMENDASI = '$kat'
 - tinggi -> cara sama kayak ()()
 - hati -hati -> cara sama kayak ()()
 - BAHAYA -> cara sama kayak ()()
 - NORMAL -> cara sama kayak ()()

	

	
	