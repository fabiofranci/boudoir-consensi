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

    .static-copy{

        font-size:15px;

        line-height:1.5;

        color:#5f4d48;

        margin:0 0 16px;
    }

</style></head>
<body class="container py-3">

@php
    $isPrimaSeduta = $tipo === 'prima_seduta';
    $isCompletamento = $tipo === 'completamento';
    $isPreesistente = in_array($tipo, ['preesistente', 'lavoro_preesistente'], true);
    $tipoValue = $isPreesistente ? 'lavoro_preesistente' : $tipo;
@endphp

<h4 class="text-center mb-3">Consenso ({{ $tipo }})</h4>

<form id="f">
    @csrf
    <input type="hidden" name="tipo" value="{{ $tipoValue }}">

<div class="step active">
    <h5>1. Dati del cliente</h5>
    @if ($isPrimaSeduta)
    <p class="static-copy">
        Questo modulo riguarda la prima seduta di dermopigmentazione e raccoglie
        i dati necessari per identificazione, valutazione preliminare e
        pianificazione del trattamento.
    </p>
    @endif
    @if ($isCompletamento)
    <p class="static-copy">
        Questo modulo riguarda la seduta di completamento, cioe la fase
        successiva alla prima seduta, e serve a confermare dati, condizioni di
        salute ed esito del lavoro gia eseguito.
    </p>
    @endif
    @if ($isPreesistente)
    <p class="static-copy">
        Questo consenso riguarda un intervento su un lavoro di dermopigmentazione
        gia esistente, realizzato in precedenza da altro operatore o in altra sede.
    </p>
    @endif
    <input name="nome" class="form-control mb-2" placeholder="Nome" required>
    <input name="cognome" class="form-control mb-2" placeholder="Cognome" required>
    <input name="codice_fiscale" class="form-control mb-2" placeholder="Codice Fiscale" required>
    <input name="data_nascita" type="date" class="form-control mb-2" required>
    <input name="telefono" class="form-control mb-2" placeholder="Telefono" required>
    <input name="email" type="email" class="form-control mb-2" placeholder="Email" required>
    @if ($isCompletamento)
    <label>Data della prima seduta</label>
    <input
        name="data_prima_seduta"
        type="date"
        class="form-control mb-2"
        required
    >
    @endif
    @if ($isPreesistente)
    <label>Data della seduta odierna</label>
    <input
        name="data_seduta"
        type="date"
        class="form-control mb-2"
        required
    >
    @endif
</div>

@if (! $isPreesistente)
<div class="step">
    <h5>{{ $isCompletamento ? '2. Natura della seduta' : '2. Trattamento richiesto' }}</h5>
    @if ($isPrimaSeduta)
    <p class="static-copy">
        Indica le caratteristiche essenziali del trattamento richiesto:
        zona, tecnica prevista, pigmenti utilizzati, data della seduta e costo
        concordato.
    </p>
    @endif
    @if ($isCompletamento)
    <p class="static-copy">
        La Seduta di Completamento è la seconda fase del trattamento di
        dermopigmentazione e costituisce parte integrante del percorso
        professionale avviato con la prima seduta.
    </p>
    <p class="static-copy">
        Non si tratta di una correzione né di un'aggiunta opzionale: è il
        completamento naturale di un processo in due tempi, progettato per
        rispettare i tempi fisiologici di guarigione e consolidamento del
        pigmento.
    </p>
    <p class="static-copy">
        In questa seduta vengono valutati l'attecchimento del pigmento, la
        distribuzione cromatica e la risposta cutanea individuale, e si
        interviene per affinare e completare il lavoro in modo definitivo. Il
        risultato finale del trattamento si valuta interamente a conclusione di
        questa fase.
    </p>
    @endif
    @if ($isPrimaSeduta)
    <input name="zona" class="form-control mb-2" placeholder="Zona trattata" required>
    <input name="tecnica" class="form-control mb-2" placeholder="Tecnica" required>
    <input name="pigmenti" class="form-control mb-2" placeholder="Pigmenti" required>
    @endif
    <label>
        {{ $isCompletamento ? 'Data seduta di completamento' : 'Data seduta' }}
    </label>
    <input name="data_seduta" type="date" class="form-control mb-2" required>
    <label>
        {{ $isCompletamento ? 'Costo seduta di completamento' : 'Costo' }}
    </label>
    <input name="costo" class="form-control mb-2" placeholder="Costo €" required>
</div>
@endif

@if ($isPrimaSeduta)
<div class="step">
    <h5>3. Informativa sul trattamento</h5>
    <p class="static-copy">
        La dermopigmentazione è una tecnica di pigmentazione cutanea
        semi-permanente che consente di migliorare, definire o ricreare
        lineamenti del viso attraverso l'introduzione di pigmenti specifici
        negli strati superficiali del derma.
    </p>
    <p class="static-copy">
        Il trattamento viene eseguito con strumenti professionali nel pieno
        rispetto delle norme igienicosanitarie vigenti.
    </p>
    <p class="static-copy">
        Il processo completo si articola in due sedute: questa prima seduta
        costituisce la fase di costruzione del trattamento. La seconda seduta,
        denominata Seduta di Completamento, viene programmata a distanza di
        circa 30-40 giorni e rappresenta parte integrante del percorso, non
        un'aggiunta opzionale.
    </p>
</div>
@endif

<div class="step">

    @if ($isPrimaSeduta)
    <h5>4. Controindicazioni e stato di salute</h5>
    <p class="static-copy">
        Dichiaro di non essere a conoscenza di condizioni che possano
        costituire controindicazione al trattamento.
    </p>
    <p class="static-copy">
        In particolare, confermo l'assenza di:
    </p>

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
    @endif

    @if ($isCompletamento)
    <h5>3. Verifica delle condizioni di salute</h5>
    <p class="static-copy">
        Il cliente dichiara che, dalla data della prima seduta a oggi, non si
        sono verificate variazioni rilevanti nello stato di salute.
    </p>
    <p class="static-copy">
        In particolare, conferma l'assenza di:
    </p>

    <label>
        <input type="checkbox" name="assenza_terapie_farmacologiche">
        Terapie farmacologiche iniziate dopo la prima seduta
    </label><br>

    <label>
        <input type="checkbox" name="assenza_interventi_estetici_laser">
        Interventi estetici o trattamenti laser nella zona trattata
    </label><br>

    <label>
        <input type="checkbox" name="assenza_reazioni_anomale">
        Reazioni anomale, infezioni o irritazioni persistenti post-prima seduta
    </label><br>

    <label>
        <input type="checkbox" name="assenza_gravidanza_sopravvenuta">
        Gravidanza sopravvenuta dalla data della prima seduta
    </label><br>

    <label class="mt-3">
        Note aggiuntive:
    </label>

    <textarea
        name="note_aggiuntive"
        class="form-control"
        rows="4"
    ></textarea>
    @endif

    @if ($isPreesistente)
    <h5>2. Anamnesi del lavoro preesistente</h5>
    <p class="static-copy">
        Il cliente dichiara quanto segue in merito alla dermopigmentazione gia presente.
    </p>

    <label>Zona trattata</label>
    <input name="zona" class="form-control mb-2" required>

    <label>Periodo approssimativo del trattamento precedente</label>
    <input
        name="periodo_approssimativo_precedente"
        class="form-control mb-2"
    >

    <label>Operatore/centro che ha eseguito il trattamento precedente</label>
    <input
        name="operatore_precedente"
        class="form-control mb-3"
    >

    <label class="mt-2 d-block">Tecnica utilizzata in precedenza (se nota)</label>
    <label><input type="checkbox" name="tecnica_microblading"> Microblading</label><br>
    <label><input type="checkbox" name="tecnica_ago_shading"> Tecnica ad ago / shading</label><br>
    <label><input type="checkbox" name="tecnica_mista"> Tecnica mista</label><br>
    <label><input type="checkbox" name="tecnica_non_nota"> Non nota</label><br>

    <label class="mt-3 d-block">Stato attuale del lavoro preesistente</label>
    <label><input type="checkbox" name="stato_sbiadito"> Sbiadito / parzialmente scomparso</label><br>
    <label><input type="checkbox" name="stato_modificato_tono_direzione"> Modificato nel tono o nella direzione</label><br>
    <label><input type="checkbox" name="stato_irregolare_forma"> Irregolare nella forma</label><br>
    <label><input type="checkbox" name="stato_pigmento_presente"> Con pigmento ancora ben presente</label><br>
    <label><input type="checkbox" name="stato_alterazione_cromatica"> Con alterazione cromatica (viraggio verso grigio, rosso, blu)</label>

    <label class="mt-3">Pigmenti o prodotti noti utilizzati in precedenza</label>
    <textarea
        name="pigmenti_precedenti"
        class="form-control"
        rows="3"
    ></textarea>
    @endif

</div>

@if ($isCompletamento)
<div class="step">
    <h5>4. Valutazione dell'esito della prima seduta</h5>
    <p class="static-copy">
        In questa sezione il tecnico annota l'esito osservato dopo la prima
        seduta e definisce gli obiettivi operativi del completamento.
    </p>

    <label>
        Note del tecnico sull'esito della prima seduta e obiettivi della seduta di completamento:
    </label>

    <textarea
        name="note_tecnico"
        class="form-control"
        rows="6"
    ></textarea>
</div>
@endif

@if ($isPrimaSeduta)
<div class="step">
    <h5>5. Rischi e possibili effetti</h5>
    <p class="static-copy">
        Il cliente dichiara di essere stato informato in merito ai possibili
        effetti temporanei del trattamento, tra cui: gonfiore, arrossamento,
        sensibilita e formazione di crosticine nei giorni successivi alla
        seduta.
    </p>
    <p class="static-copy">
        Tali manifestazioni rientrano nella normale risposta cutanea e si
        risolvono spontaneamente nel rispetto delle istruzioni post-trattamento.
    </p>
    <p class="static-copy">
        Il risultato finale e influenzato da fattori individuali quali il tipo
        di pelle, il metabolismo, la risposta tissutale e il rispetto delle
        indicazioni fornite. Variazioni nella resa cromatica rispetto alle
        aspettative iniziali possono essere affrontate nella Seduta di
        Completamento.
    </p>

    <h5 class="mt-4">6. Istruzioni post-seduta</h5>
    <p class="static-copy">
        Il cliente si impegna a rispettare le seguenti indicazioni nelle 2
        settimane successive alla seduta:
    </p>

    <label><input type="checkbox" name="post_non_bagnare" required> Non bagnare e non sfregare la zona trattata per almeno 7 giorni</label><br>
    <label><input type="checkbox" name="post_non_trucco" required> Non applicare trucco, creme o prodotti non indicati dal tecnico</label><br>
    <label><input type="checkbox" name="post_non_sole" required> Non esporre la zona al sole, solarium o saune</label><br>
    <label><input type="checkbox" name="post_non_staccare_crosticine" required> Non staccare le crosticine e lasciarle cadere naturalmente</label><br>
    <label><input type="checkbox" name="post_prodotto_lenitivo" required> Applicare il prodotto lenitivo consigliato secondo le modalita indicate</label>
</div>
@endif

@if ($isPreesistente)
<div class="step">
    <h5>3. Obiettivo dell'intervento odierno</h5>
    <p class="static-copy">
        Questa sezione serve a chiarire sia gli eventuali trattamenti gia eseguiti
        sul lavoro preesistente, sia l'obiettivo dell'intervento richiesto oggi.
    </p>

    <label class="mt-2 d-block">Eventuali trattamenti di rimozione gia effettuati</label><br>
    <label><input type="checkbox" name="rimozione_nessuno"> Nessuno</label><br>
    <label><input type="checkbox" name="rimozione_laser"> Rimozione laser</label><br>
    <label><input type="checkbox" name="rimozione_soluzione_salina"> Rimozione con soluzione salina</label><br>
    <label><input type="checkbox" name="rimozione_altro"> Altro</label>
    <input
        name="rimozione_altro_testo"
        class="form-control mb-3"
        placeholder="Specificare altro"
    >

    <label class="mt-2 d-block">Il trattamento richiesto ha come scopo</label><br>
    <label><input type="checkbox" name="obiettivo_rinfresco"> Rinfresco / reintegrazione cromatica del lavoro esistente</label><br>
    <label><input type="checkbox" name="obiettivo_ridisegno"> Ridisegno e correzione della forma</label><br>
    <label><input type="checkbox" name="obiettivo_copertura"> Copertura del pigmento precedente</label><br>
    <label><input type="checkbox" name="obiettivo_neutralizzazione"> Neutralizzazione del viraggio cromatico</label><br>
    <label><input type="checkbox" name="obiettivo_completamento"> Completamento di un lavoro lasciato a meta</label><br>
    <label><input type="checkbox" name="obiettivo_altro"> Altro</label>
    <input
        name="obiettivo_altro_testo"
        class="form-control mb-3"
        placeholder="Specificare altro"
    >

    <label>Note del tecnico sull'obiettivo e la strategia di intervento</label>
    <textarea
        name="note_tecnico_intervento"
        class="form-control mb-3"
        rows="5"
    ></textarea>

    <label>Costo seduta odierna</label>
    <input name="costo" class="form-control" placeholder="Costo €" required>
</div>

<div class="step">
    <h5>4. Limitazioni, rischi specifici e previsione dei risultati</h5>
    <p class="static-copy">
        Il cliente dichiara di essere stato informato che gli interventi su
        dermopigmentazioni preesistenti comportano variabili non completamente
        prevedibili, legate alla natura del pigmento gia presente, alla risposta
        cutanea individuale e all'entita del lavoro da gestire.
    </p>
    <p class="static-copy">
        In particolare, il cliente comprende e accetta che:
    </p>

    <label><input type="checkbox" name="presa_atto_limite_toni" required> La presenza di pigmento preesistente puo limitare la possibilita di ottenere determinati toni cromatici</label><br>
    <label><input type="checkbox" name="presa_atto_piu_sedute" required> Possono rendersi necessarie piu sedute successive per raggiungere il risultato desiderato</label><br>
    <label><input type="checkbox" name="presa_atto_neutralizzazione_tempi" required> In caso di viraggio cromatico la neutralizzazione richiede tempi tecnici non comprimibili</label><br>
    <label><input type="checkbox" name="presa_atto_risultato_non_comparabile" required> Il risultato finale non e comparabile a quello ottenibile su pelle vergine</label><br>
    <label><input type="checkbox" name="presa_atto_non_responsabilita" required> Boudoir 31 non e responsabile degli esiti derivanti dal trattamento preesistente</label>

    <h5 class="mt-4">5. Numero previsto di sedute e piano di intervento</h5>
    <p class="static-copy">
        In base alla valutazione iniziale, il tecnico indica il piano di intervento
        proposto e una previsione orientativa delle sedute successive.
    </p>

    <label class="mt-3">Piano di intervento previsto</label>
    <textarea
        name="piano_intervento"
        class="form-control mb-3"
        rows="5"
    ></textarea>

    <label>Numero minimo di sedute previste</label>
    <input name="numero_minimo_sedute" class="form-control mb-2">

    <label>Costo indicativo per seduta</label>
    <input name="costo_indicativo_seduta" class="form-control mb-2" placeholder="Costo €">

    <label>Intervallo tra le sedute</label>
    <input name="intervallo_sedute" class="form-control">
</div>

<div class="step">
    <h5>6. Stato di salute e controindicazioni</h5>
    <p class="static-copy">
        Il cliente dichiara l'assenza di condizioni che possano costituire
        controindicazione. Conferma inoltre:
    </p>

    <label><input type="checkbox" name="nessuna_terapia_anticoagulante"> Nessuna terapia anticoagulante in corso</label><br>
    <label><input type="checkbox" name="nessuna_terapia_isotretinoina"> Nessuna terapia con isotretinoina in corso o negli ultimi 12 mesi</label><br>
    <label><input type="checkbox" name="nessun_trattamento_laser_peeling"> Nessun trattamento laser o peeling sulla zona negli ultimi 30 giorni</label><br>
    <label><input type="checkbox" name="nessuna_allergia_pigmenti"> Nessuna allergia nota ai componenti dei pigmenti</label>

    <label class="mt-3">Farmaci in uso o condizioni rilevanti</label>
    <textarea
        name="farmaci_condizioni_rilevanti"
        class="form-control mb-3"
        rows="4"
    ></textarea>

    <h5 class="mt-4">7. Privacy e documentazione fotografica</h5>
    <p class="static-copy">
        I seguenti consensi riguardano il trattamento dei dati personali e
        l'eventuale utilizzo delle immagini raccolte nel corso del servizio.
    </p>
    <label><input type="checkbox" name="privacy_dati"> Acconsento al trattamento dei dati personali</label><br>
    <label><input type="checkbox" name="privacy_foto"> Acconsento all'acquisizione di immagini fotografiche a scopo documentale</label><br>
    <label><input type="checkbox" name="privacy_promo"> Acconsento all'utilizzo delle immagini a fini promozionali</label>
</div>
@endif

@if (! $isPreesistente)
<div class="step">
    @if ($isPrimaSeduta)
    <h5>7. Privacy e trattamento dei dati</h5>
    <p class="static-copy">
        I dati personali raccolti sono trattati esclusivamente per finalita
        connesse all'erogazione del servizio, nel rispetto del Regolamento (UE)
        2016/679 (GDPR). Non vengono ceduti a terzi senza esplicito consenso.
    </p>
    <p class="static-copy">
        Le immagini eventualmente acquisite a fini documentali sono archiviate
        in modo sicuro e non utilizzate a scopi promozionali senza
        autorizzazione scritta.
    </p>
    @endif
    @if ($isCompletamento)
    <h5>5. Rischi e aspettative</h5>
    <p class="static-copy">
        Il cliente conferma di essere a conoscenza che anche la seduta di
        completamento puo dare luogo a effetti temporanei quali gonfiore,
        sensibilita e formazione di crosticine, del tutto comparabili a quelli
        gia sperimentati.
    </p>
    <p class="static-copy">
        Le istruzioni post-seduta rimangono invariate rispetto a quelle fornite
        alla prima seduta. Il risultato definitivo del trattamento sara
        valutabile tra i 30 e i 60 giorni successivi a questa seduta, una volta
        completata la fase di guarigione e stabilizzazione del pigmento.
    </p>
    <h5 class="mt-4">Privacy</h5>
    <p class="static-copy">
        I consensi seguenti riguardano il trattamento dei dati personali e
        l'eventuale acquisizione o utilizzo delle immagini raccolte durante il
        percorso di dermopigmentazione.
    </p>
    @endif
    <label><input type="checkbox" name="privacy_dati"> Consenso dati</label><br>
    <label><input type="checkbox" name="privacy_foto"> Foto documentali</label><br>
    <label><input type="checkbox" name="privacy_promo"> Uso promozionale</label>
</div>
@endif

<div class="step">
    <h5>
        {{
            $isPreesistente
                ? '8. Dichiarazione e firma'
                : ($isCompletamento ? '6. Dichiarazione e firma' : '8. Dichiarazione e firma')
        }}
    </h5>
    @if ($isPrimaSeduta)
    <p class="static-copy">
        Il/La sottoscritto/a dichiara di aver ricevuto informazioni chiare sul
        trattamento richiesto, sulle possibili reazioni e sulle indicazioni
        post-trattamento, e di prestare il proprio consenso informato.
    </p>
    @endif
    @if ($isCompletamento)
    <p class="static-copy">
        Il/La sottoscritto/a dichiara di aver letto e compreso integralmente il
        presente modulo e di acconsentire all'esecuzione della Seduta di
        Completamento cosi descritta.
    </p>
    @endif
    @if ($isPreesistente)
    <p class="static-copy">
        Il/La sottoscritto/a dichiara di aver ricevuto tutte le informazioni
        necessarie, di aver compreso le limitazioni specifiche di un intervento
        su lavoro preesistente e di acconsentire liberamente all'esecuzione del
        trattamento secondo quanto concordato.
    </p>
    @endif
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

            if($(this).is(':checkbox')){

                if(!$(this).is(':checked')){

                    $(this).addClass('is-invalid');

                    valid = false;

                }else{

                    $(this).removeClass('is-invalid');
                }

                return;
            }

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
        'post_non_bagnare',
        'post_non_trucco',
        'post_non_sole',
        'post_non_staccare_crosticine',
        'post_prodotto_lenitivo',
        'tecnica_microblading',
        'tecnica_ago_shading',
        'tecnica_mista',
        'tecnica_non_nota',
        'stato_sbiadito',
        'stato_modificato_tono_direzione',
        'stato_irregolare_forma',
        'stato_pigmento_presente',
        'stato_alterazione_cromatica',
        'rimozione_nessuno',
        'rimozione_laser',
        'rimozione_soluzione_salina',
        'rimozione_altro',
        'obiettivo_rinfresco',
        'obiettivo_ridisegno',
        'obiettivo_copertura',
        'obiettivo_neutralizzazione',
        'obiettivo_completamento',
        'obiettivo_altro',
        'presa_atto_limite_toni',
        'presa_atto_piu_sedute',
        'presa_atto_neutralizzazione_tempi',
        'presa_atto_risultato_non_comparabile',
        'presa_atto_non_responsabilita',
        'nessuna_terapia_anticoagulante',
        'nessuna_terapia_isotretinoina',
        'nessun_trattamento_laser_peeling',
        'nessuna_allergia_pigmenti',
        'assenza_terapie_farmacologiche',
        'assenza_interventi_estetici_laser',
        'assenza_reazioni_anomale',
        'assenza_gravidanza_sopravvenuta',
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
