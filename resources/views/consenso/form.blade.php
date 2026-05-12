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

    body{

        background:
            linear-gradient(
                rgba(255,248,245,.92),
                rgba(255,248,245,.95)
            ),
            url('/images/bg.jpg');

        background-size:cover;
        background-position:center;

        font-family:"Helvetica Neue", sans-serif;

        color:#4b3b38;

        min-height:100vh;
    }

    .main-box{

        max-width:850px;

        margin:40px auto;

        background:rgba(255,255,255,.82);

        backdrop-filter: blur(10px);

        border-radius:28px;

        padding:35px;

        box-shadow:
            0 10px 40px rgba(0,0,0,.10);
    }

    h4{

        text-align:center;

        font-weight:300;

        letter-spacing:2px;

        margin-bottom:30px;

        color:#4b3b38;
    }

    h5{

        color:#b78479;

        margin-bottom:20px;

        font-weight:600;
    }

    .step{
        display:none;
    }

    .step.active{
        display:block;
    }

    .form-control,
    textarea,
    select{

        border-radius:14px;

        border:1px solid #e2cfc9;

        padding:12px 14px;

        font-size:16px;

        background:#fffefd;
    }

    .form-control:focus{

        border-color:#c69b91;

        box-shadow:
            0 0 0 .2rem rgba(198,155,145,.18);
    }

    label{

        margin-bottom:12px;

        font-size:16px;
    }

    input[type=checkbox]{

        transform:scale(1.2);

        margin-right:10px;

        accent-color:#c69b91;
    }

    #sig{

        border:2px dashed #d9b9b0;

        border-radius:18px;

        background:white;

        width:100%;

        max-width:420px;

        height:180px;
    }

    .nav-btns{

        display:flex;

        justify-content:space-between;

        margin-top:30px;
    }

    .btn{

        border-radius:14px;

        padding:10px 20px;

        border:none;

        font-weight:500;
    }

    .btn-primary{

        background:#c69b91;
    }

    .btn-primary:hover{

        background:#b78479;
    }

    .btn-success{

        background:#8fbc8f;
    }

    .btn-success:hover{

        background:#7aa77a;
    }

    .btn-secondary{

        background:#b7aaa6;
    }

    .btn-warning{

        background:#e9c9b7;

        color:#4b3b38;
    }

    .progress{

        height:10px;

        border-radius:30px;

        background:#eee;

        margin-bottom:30px;
    }

    .progress-bar{

        background:#c69b91;
    }

</style></head>
<body class="container py-3">

<h4 class="text-center mb-3">Consenso ({{ $tipo }})</h4>

<form id="f">
    @csrf
    <input type="hidden" name="tipo" value="{{ $tipo }}">

<div class="step active">
    <h5>1. Cliente</h5>
    <input name="nome" class="form-control mb-2" placeholder="Nome" required>
    <input name="cognome" class="form-control mb-2" placeholder="Cognome" required>
    <input name="codice_fiscale" class="form-control mb-2" placeholder="Codice Fiscale" required>
    <input name="data_nascita" type="date" class="form-control mb-2" required>
    <input name="telefono" class="form-control mb-2" placeholder="Telefono" required>
    <input name="email" type="email" class="form-control mb-2" placeholder="Email" required>
</div>

<div class="step">
    <h5>2. Trattamento</h5>
    <input name="zona" class="form-control mb-2" placeholder="Zona trattata" required>
    <input name="tecnica" class="form-control mb-2" placeholder="Tecnica" required>
    <input name="pigmenti" class="form-control mb-2" placeholder="Pigmenti" required>
    <input name="data_seduta" type="date" class="form-control mb-2"    required>
    <input name="costo" class="form-control mb-2" placeholder="Costo €"     required>
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
    if(i > 0){
        $('#prev').css('visibility', 'visible');
    }else{
        $('#prev').css('visibility', 'hidden');
    }
    $('#next').toggle(i<steps.length-1);
    $('#save').toggle(i===steps.length-1);
}
$('#next').click(function(){

    let valid = true;

    steps.eq(i)
        .find('[required]')
        .each(function(){

            if(!$(this).val()){

                $(this).addClass('is-invalid');

                valid = false;

            }else{

                $(this).removeClass('is-invalid');
            }
        });

    if(!valid){
        return;
    }

    i++;

    show();
});
$('#prev').click(()=>{i--;show()});
show();

// signature
const pad = new SignaturePad(document.getElementById('sig'));
$('#clear').click(()=>pad.clear());

// submit
$('#f').on('submit', function(e){

    e.preventDefault();

    // firma obbligatoria

    if (pad.isEmpty()) {

        alert('Inserire la firma del cliente');

        return;
    }

    $('#firma_cliente').val(
        pad.toDataURL()
    );

    let data = {};

    $(this).serializeArray().forEach(({name,value})=>{

        if (data[name] !== undefined) {

            if (!Array.isArray(data[name])) {
                data[name] = [data[name]];
            }

            data[name].push(value);

        } else {

            data[name] = value;
        }
    });

    [
        'tspciglia',
        'tsplabbra',
        'tspeyeliner',
        'privacy_dati',
        'privacy_foto',
        'privacy_promo'
    ].forEach(k=>{

        data[k] = $('input[name="'+k+'"]').is(':checked')
            ? 1
            : 0;
    });

    $.post('/consenso/salva', data, function(res){

        window.location.href =
            '/consenso/pdf/' + res.id;
    });
});
</script>

</body>
</html>