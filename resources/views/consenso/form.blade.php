<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Consenso - {{ $tipo }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <style>
        .step{display:none}
        .step.active{display:block}
        input, select, textarea{font-size:18px;padding:10px}
        #sig{border:1px solid #000;width:100%;max-width:420px;height:180px}
        .nav-btns{display:flex;justify-content:space-between;margin-top:16px}
    </style>
</head>
<body class="container py-3">

<h4 class="text-center mb-3">Consenso ({{ $tipo }})</h4>

<form id="f">
    @csrf
    <input type="hidden" name="tipo" value="{{ $tipo }}">

<div class="step active">
    <h5>1. Cliente</h5>
    <input name="nome" class="form-control mb-2" placeholder="Nome">
    <input name="cognome" class="form-control mb-2" placeholder="Cognome">
    <input name="codice_fiscale" class="form-control mb-2" placeholder="Codice Fiscale">
    <input name="data_nascita" type="date" class="form-control mb-2">
    <input name="telefono" class="form-control mb-2" placeholder="Telefono">
    <input name="email" type="email" class="form-control mb-2" placeholder="Email">
</div>

<div class="step">
    <h5>2. Trattamento</h5>
    <input name="zona" class="form-control mb-2" placeholder="Zona trattata">
    <input name="tecnica" class="form-control mb-2" placeholder="Tecnica">
    <input name="pigmenti" class="form-control mb-2" placeholder="Pigmenti">
    <input name="data_seduta" type="date" class="form-control mb-2">
    <input name="costo" class="form-control mb-2" placeholder="Costo €">
</div>

<div class="step">

    <h5>3. Anamnesi e controindicazioni</h5>

    <label>
        <input type="checkbox" name="gravidanza_allattamento">
        Gravidanza o allattamento
    </label><br>

    <label>
        <input type="checkbox" name="malattie_autoimmuni">
        Malattie autoimmuni in fase attiva
    </label><br>

    <label>
        <input type="checkbox" name="coagulopatie">
        Coagulopatie o terapie anticoagulanti
    </label><br>

    <label>
        <input type="checkbox" name="patologie_cutanee">
        Patologie cutanee attive nella zona di trattamento
    </label><br>

    <label>
        <input type="checkbox" name="allergie">
        Allergie note a pigmenti, nichel, anestetici topici
    </label><br>

    <label>
        <input type="checkbox" name="isotretinoina">
        Terapie con isotretinoina negli ultimi 12 mesi
    </label><br>

    <label>
        <input type="checkbox" name="laser_peeling">
        Laser, peeling o filler negli ultimi 30 giorni
    </label><br>

    <hr>

    <label class="mt-3">
        Condizioni mediche rilevanti:
    </label>

    <textarea
        name="condizioni_mediche"
        class="form-control mb-3"
        rows="3"
    ></textarea>

    <label>
        Farmaci in uso:
    </label>

    <textarea
        name="farmaci_uso"
        class="form-control"
        rows="3"
    ></textarea>

</div>

<div class="step">
    <h5>4. Privacy</h5>
    <label><input type="checkbox" name="privacy_dati"> Consenso dati</label><br>
    <label><input type="checkbox" name="privacy_foto"> Foto documentali</label><br>
    <label><input type="checkbox" name="privacy_promo"> Uso promozionale</label>
</div>

<div class="step">
    <h5>5. Firma</h5>
    <canvas id="sig" width="420" height="180"></canvas><br>
    <button type="button" id="clear" class="btn btn-warning mt-2">Cancella</button>
    <input type="hidden" name="firma_cliente" id="firma_cliente">
</div>

<div class="nav-btns">
    <button type="button" id="prev" class="btn btn-secondary">Indietro</button>
    <button type="button" id="next" class="btn btn-primary">Avanti</button>
    <button type="submit" id="save" class="btn btn-success" style="display:none">Salva</button>
</div>

</form>

<script>
let i=0, steps=$('.step');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function show(){
    steps.removeClass('active').eq(i).addClass('active');
    $('#prev').toggle(i>0);
    $('#next').toggle(i<steps.length-1);
    $('#save').toggle(i===steps.length-1);
}
$('#next').click(()=>{i++;show()});
$('#prev').click(()=>{i--;show()});
show();

// signature
const pad = new SignaturePad(document.getElementById('sig'));
$('#clear').click(()=>pad.clear());

// submit
$('#f').on('submit', function(e){
    e.preventDefault();

    $('#firma_cliente').val(pad.toDataURL());

    // serialize + checkbox 1/0
    let data = {};
    $(this).serializeArray().forEach(({name,value})=>{
        if (data[name] !== undefined) {
            if (!Array.isArray(data[name])) data[name] = [data[name]];
            data[name].push(value);
        } else {
            data[name] = value;
        }
    });

    // for each checkbox ensure 1/0
    [
    'tspciglia',
    'tsplabbra',
    'tspeyeliner',

    'gravidanza_allattamento',
    'malattie_autoimmuni',
    'coagulopatie',
    'patologie_cutanee',
    'allergie',
    'isotretinoina',
    'laser_peeling',

    'privacy_dati',
    'privacy_foto',
    'privacy_promo'
    ].forEach(k=>{
            data[k] = $('input[name="'+k+'"]').is(':checked') ? 1 : 0;
        });

    $.post('/consenso/salva', data, function(res){
        alert('Salvato!');
        window.location.href = '/consenso/pdf/'+res.id;
    });
});
</script>

</body>
</html>