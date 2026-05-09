<style>
body{font-family:helvetica;font-size:11px}
.title{text-align:center;font-weight:bold;font-size:16px}
.sub{text-align:center;font-size:12px}
.sec{margin-top:12px}
.sec b{font-size:12px}
.row{margin:3px 0}
</style>

<div class="title">BOUDOIR 31</div>
<div class="sub">Centro di Dermopigmentazione Avanzata · Lucca</div>
<br>
<div class="title">MODULO DI CONSENSO INFORMATO</div>
<div class="sub">Prima Seduta di Dermopigmentazione</div>

<div class="sec">
<b>1. DATI DEL CLIENTE</b>
<div class="row">Nome e Cognome: {{ $data['nome'] ?? '' }} {{ $data['cognome'] ?? '' }}</div>
<div class="row">Data di nascita: {{ $data['data_nascita'] ?? '' }}</div>
<div class="row">Telefono: {{ $data['telefono'] ?? '' }}</div>
<div class="row">Email: {{ $data['email'] ?? '' }}</div>
</div>

<div class="sec">
<b>2. TRATTAMENTO RICHIESTO</b>
<div class="row">Zona: {{ $data['zona'] ?? '' }}</div>
<div class="row">Tecnica: {{ $data['tecnica'] ?? '' }}</div>
<div class="row">Pigmenti: {{ $data['pigmenti'] ?? '' }}</div>
<div class="row">Data: {{ $data['data_seduta'] ?? '' }}</div>
<div class="row">Costo: € {{ $data['costo'] ?? '' }}</div>
</div>

<div class="sec">
<b>TIPO TRATTAMENTO</b>
<div>{{ !empty($data['tspciglia']) ? '☑' : '☐' }} Sopracciglia</div>
<div>{{ !empty($data['tsplabbra']) ? '☑' : '☐' }} Labbra</div>
<div>{{ !empty($data['tspeyeliner']) ? '☑' : '☐' }} Eyeliner</div>
</div>

<div class="sec">
<b>PRIVACY</b>
<div>{{ !empty($data['privacy_dati']) ? '☑' : '☐' }} Consenso dati</div>
<div>{{ !empty($data['privacy_foto']) ? '☑' : '☐' }} Foto documentali</div>
<div>{{ !empty($data['privacy_promo']) ? '☑' : '☐' }} Uso promozionale</div>
</div>

<div class="sec">
<b>FIRMA</b><br>
<img src="{{ $data['firma_cliente'] ?? '' }}" height="80">
</div>