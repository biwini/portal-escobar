<?php 

// foreach ($_SERVER as $key => $value) {
//     echo $key.' => '.$value.'<hr>';
// }
?>


<!-- 
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <style type="text/css">
    /* CSS principal da barra de progresso. Não deve ser alterado. */

ol.step-progress-bar {
    list-style: none;
    padding: 0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

ol.step-progress-bar li {
    display: inline-block;
    vertical-align: top;
    text-align: center;
    flex: 1 1;
    position: relative;
    margin: 0 5px 0;
}

ol.step-progress-bar li span.content-bullet {
    border-radius: 100%;
    display: block;
    text-align: center;
    transform: translateX(-50%);
    margin-left: 50%;
}

ol.step-progress-bar li span.content-wrapper {
    display: inline-block;
    overflow: visible;
    width: 100%;
    padding: 0;
}

ol.step-progress-bar li span.content-stick {
    position: absolute;
    display: block;
    width: 100%;
    height: 8px;
    z-index: -1;
    transform: translate(-50%, -50%);
}

/* Cores. Sinta-se livre para alterar. */

/* Cor padrão.
   Passado: #2dcd73 (verde) e branco.
   Presente: #4c92d9 (azul) e branco.
   Futuro: #dde2e3 (cinza claro) e #869398 (cinza escuro).
*/

ol.step-progress-bar li.step-past *,
ol.step-progress-bar li.step-present .content-stick {
    color: #2dcd73;
    background: #2dcd73;
}

ol.step-progress-bar li.step-present * {
    color: #4c92d9;
    background: #4c92d9;
}

ol.step-progress-bar li .content-bullet {
    color: white;
}

ol.step-progress-bar li.step-future * {
    color: #869398;
    background: #dde2e3;
}

ol.step-progress-bar li .content-wrapper {
    background: transparent;
}

/* Cor especial 1.
   Passado: vemelho
   Presente: laranja
   Futuro: amarelo
   Cor dos números: azul
*/

ol.step-progress-bar.cor-especial li.step-past *,
ol.step-progress-bar.cor-especial li.step-present .content-stick {
    color: red;
    background: red;
}

ol.step-progress-bar.cor-especial li.step-present * {
    color: orange;
    background: orange;
}

ol.step-progress-bar.cor-especial li.step-future * {
    color: yellow;
    background: yellow;
}

ol.step-progress-bar.cor-especial li .content-bullet {
    color: blue;
}

ol.step-progress-bar.cor-especial li .content-wrapper {
    background: transparent;
}

/* Cor especial 2. */

#gelado * {
    color: blue;
    background: blue;
}

#frio * {
    color: cyan;
    background: cyan;
}

#morno * {
    color: lime;
    background: lime;
}

#quente * {
    color: yellow;
    background: yellow;
}

#fervendo * {
    color: red;
    background: red;
}

#cores-temperatura .content-wrapper {
    background: transparent;
}

#cores-temperatura .content-bullet {
    color: black;
}

#cores-temperatura .content-wrapper {
    text-shadow: 0 0 1px black, 0 0 8px purple;
}

#cores-temperatura li.step-present {
    font-weight: bold;
    font-size: 120%;
}

#cores-temperatura li.step-present .content-bullet {
    width: 55px;
    line-height: 55px;
    transform: translate(-27px, -9px);
    font-size: 200%;
    color: pink;
    text-shadow: 0 1px black, 1px 0 black, -1px 0 black, 0 -1px black;
}

#cores-temperatura li .content-stick {
    background: purple;
}

/* Tamanhos. */

/* Tamanho pequeno:
   Bolinha de 25px de diâmetro.
   Fonte 75%.
   Conector 4px de altura.
*/

ol.step-progress-bar.small li .content-bullet {
    width: 25px;
    line-height: 25px;
}

ol.step-progress-bar.small li {
    font-size: 75%;
}

ol.step-progress-bar.small li .content-stick {
    top: 12.5px; /* Metade do diâmetro. */
    height: 4px;
}

/* Tamanho médio:
   Bolinha de 37px de diâmetro.
   Fonte 100%.
   Conector 6px de altura.
*/

ol.step-progress-bar.mid li .content-bullet {
    width: 37px;
    line-height: 37px;
}

ol.step-progress-bar.mid li {
    font-size: 100%;
}

ol.step-progress-bar.mid li .content-stick {
    top: 18.5px; /* Metade do diâmetro. */
    height: 6px;
}

/* Tamanho grande:
   Bolinha de 49px de diâmetro.
   Fonte 120%.
   Conector 8px de altura.
*/

ol.step-progress-bar.large li .content-bullet {
    width: 49px;
    line-height: 49px;
}

ol.step-progress-bar.large li {
    font-size: 125%;
}

ol.step-progress-bar.large li .content-stick {
    top: 24.5px; /* Metade do diâmetro. */
    height: 8px;
}
  </style>
</head>
<body>

    <div id="dom-target" style="display: none;">
    <?php
        $output = "42"; // Again, do some operation, get the output.
        echo htmlspecialchars($output); /* You have to escape because the result
                                           will not be valid HTML otherwise. */
    ?>
</div>
<script>
    var div = document.getElementById("dom-target");
    var myData = div.textContent;
</script>

<h3>Exemplo 1, uma barra simples</h3>

<ol id="barra-simples-1" class="small">
    <li>Passo 1</li>
    <li>Passo 2</li>
    <li>Passo 3</li>
</ol>

<h3>Exemplo 2, uma barra simples com progresso definido no HTML</h3>

<ol id="barra-simples-2" class="mid">
    <li>A</li>
    <li class="step-present">B</li>
    <li>C</li>
</ol>

<h3>Exemplo 3, uma barra simples com progresso definido no JS</h3>

<ol id="barra-simples-3" class="large">
    <li>Cadastrar os dados</li>
    <li>Confirmar o e-mail</li>
    <li>Efetuar a compra</li>
    <li>Realizar o pagamento</li>
</ol>

<h3>Exemplo 4: Com botões de controle</h3>

<ol id="barra-controle-basico" class="mid">
    <li>Preparar</li>
    <li>Apontar</li>
    <li>Fogo</li>
</ol>

<div id="controle-basico"></div>

<h3>Exemplo 5: Com botões de controle personalizados</h3>

<ol id="barra-controle-personalizado" class="large">
    <li>Nova</li>
    <li>Crescente</li>
    <li>Cheia</li>
    <li>Minguante</li>
</ol>

<div id="controle-personalizado"></div>

<h3>Exemplo 6: Fazendo um macarrão instantâneo</h3>

<ol id="barra-macarrao" class="small">
    <li>Colocar água na panela</li>
    <li>Ligar o fogo</li>
    <li>Ferver</li>
    <li>Acrescentar o macarrão</li>
    <li>Cozinhar por 3 minutos</li>
    <li>Desligar o fogo</li>
    <li>Acrescentar o tempero</li>
    <li>Servir</li>
</ol>

<div id="controle-macarrao"></div>

<h3>Exemplo 7: Cores personalizadas 1</h3>

<ol id="cores-personalizadas" class="large cor-especial">
    <li>Juntar dinheiro</li>
    <li>Construir robôs</li>
    <li>Declarar guerra</li>
    <li>Atacar os inimigos</li>
    <li>Dominar o mundo</li>
</ol>

<h3>Exemplo 8: Cores personalizadas 2</h3>

<ol id="cores-temperatura" class="mid">
    <li id="gelado">Gelado</li>
    <li id="frio">Frio</li>
    <li id="morno">Morno</li>
    <li id="quente">Quente</li>
    <li id="fervendo">Fervendo</li>
</ol>

<div id="controle-temperatura"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script type="text/javascript">
    /* JavaScript para incluir. */

jQuery.fn.extend({
    stepProgressBar: function(currentStep) {
        currentStep = currentStep || this.currentStep() || 1;
        let childs = this
                .addClass("step-progress-bar")
                .find("li")
                .removeClass("step-past step-present step-future");

        childs.find(".content-stick").removeClass("step-past step-future");

        let size = childs.length < 1 ? 100 : 100 / childs.length;
        childs.css("width", size + "%");

        for (let i = 0; i < childs.length; i++) {
            let child = $(childs[i]);
            if (child.find("span.content-wrapper").length === 0) {
                child.wrapInner("<span class='content-wrapper'></span>");
                if (i > 0) child.append("<span class='content-stick'></span>");
                child.prepend("<span class='content-bullet'>" + (i + 1) + "</span>");
            }
            let stepName = i < currentStep - 1 ? "step-past"
                    : i === currentStep - 1 ? "step-present"
                    : "step-future";
            child.addClass(stepName);
            if (i > 0) {
                let stickName = stepName === "step-present" ? "step-past" : stepName;
                child.find(".content-stick").addClass(stickName);
            }
            child.css("z-index", childs.length - i);
            child.find(":before").css("z-index", childs.length - i + 2);
        }
        return this;
    },

    currentStep: function() {
        var childs = this.find("li");
        for (let i = 0; i < childs.length; i++) {
            if ($(childs[i]).is(".step-present")) return i + 1;
        }
        return 1;
    },

    countSteps: function() {
        return this.find("li").length;
    },

    isFirstStep: function() {
        return this.countSteps() === 1;
    },

    isLastStep: function() {
        return this.countSteps() === this.currentStep();
    },

    previousStep: function() {
        if (!this.isFirstStep()) this.stepProgressBar(this.currentStep() - 1);
    },

    nextStep: function() {
        if (!this.isLastStep()) this.stepProgressBar(this.currentStep() + 1);
    },

    rewind: function() {
        this.stepProgressBar(1);
    },

    fastForward: function() {
        this.stepProgressBar(this.countSteps());
    },

    controlProgressBar: function(progressBar) {
        let rewind = function() { progressBar.rewind(); };
        let next = function() { progressBar.nextStep(); };
        let previous = function() { progressBar.previousStep(); };
        let fastForward = function() { progressBar.fastForward(); };
        this.empty();
        $("<input type='button' class='step-progress-bar-button rewind' value='⏪' />").on('click', rewind).appendTo(this);
        $("<input type='button' class='step-progress-bar-button previous' value='◀️' />").on('click', previous).appendTo(this);
        $("<input type='button' class='step-progress-bar-button next' value='▶' />").on('click', next).appendTo(this);
        $("<input type='button' class='step-progress-bar-button fast-forward' value='⏩' />").on('click', fastForward).appendTo(this);
        return this;
    }
});

/* JavaScript na página. */

$("#barra-simples-1").stepProgressBar();
$("#barra-simples-2").stepProgressBar();
$("#barra-simples-3").stepProgressBar(3);

let pb = $("#barra-controle-basico").stepProgressBar(2);
$("#controle-basico").controlProgressBar(pb);

$("#controle-macarrao").controlProgressBar($("#barra-macarrao").stepProgressBar(4));

let ca = $("#barra-controle-personalizado").stepProgressBar(1);
let cb = $("#controle-personalizado").controlProgressBar(ca);
cb.find(".rewind, .fast-forward").remove();

$("#cores-personalizadas").stepProgressBar(4);

let temp = $("#cores-temperatura").stepProgressBar(3);
$("#controle-temperatura").controlProgressBar(temp);
  </script>
</body>
</html> -->

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

<form action="Registro" method="post"> --><!-- registro -->
<!-- 
   <div class="form-group">
   <label for="exampleInputEmail1">Nombre</label>
   <input type="email" class="form-control" name="nombre" placeholder="Email">
   </div>

   <div class="form-group">
      <label for="exampleInputEmail1">Apellidos</label>
      <input type="email" class="form-control" name="nombre" placeholder="Email">
   </div>

    <div class="form-group">                                        
      <label for="exampleInputEmail1">Area</label><br/>
     <select class="selectpicker" data-live-search="true">
      <option data-tokens="ketchup mustard">Hot Dog, Fries and a Soda</option>
      <option data-tokens="mustard,hola">Burger, Shake and a Smile</option>
      <option data-tokens="frosting,hola,puto">Sugar, Spice and all things nice</option>
    </select>
                                         
     </div>

     <div class="form-group">
         <label for="exampleInputEmail1">Corrre electronico</label>
         <input type="email" class="form-control" name="apellidos" placeholder="Email">
     </div>


      <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
           <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
          <input type="hidden"  name="tipo" value="1">                                                                                                                    
      </div>

    <button type="button" class="btn btn-default">Cancelar</button>
     <button type="submit" class="btn btn-success">Registrarme</button>
</form> --><!-- Cierrra form registro -->

<html>

<head>
    <title>Line Chart</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="js/Chart.bundle.min.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <!-- <script src="../../utils.js"></script> -->
    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>

<body>
    <div style="width:1000px">
        <p>This example demonstrates a time series scale by drawing a financial line chart using just the core library. For more specific functionality for financial charts, please see <a href="https://github.com/chartjs/chartjs-chart-financial">chartjs-chart-financial</a></p>
        <canvas id="chart1"></canvas>
    </div>
    <br>
    <br>
    Chart Type:
    <select id="type">
        <option value="line">Line</option>
        <option value="bar">Bar</option>
    </select>
    <select id="unit">
        <option value="second">Second</option>
        <option value="minute">Minute</option>
        <option value="hour">Hour</option>
        <option value="day" selected>Day</option>
        <option value="month">Month</option>
        <option value="year">Year</option>
    </select>
    <button id="update">update</button>

    <script>
        function generateData() {
            var unit = document.getElementById('unit').value;

            function unitLessThanDay() {
                return unit === 'second' || unit === 'minute' || unit === 'hour';
            }

            function beforeNineThirty(date) {
                return date.hour() < 9 || (date.hour() === 9 && date.minute() < 30);
            }

            // Returns true if outside 9:30am-4pm on a weekday
            function outsideMarketHours(date) {
                if (date.isoWeekday() > 5) {
                    return true;
                }
                if (unitLessThanDay() && (beforeNineThirty(date) || date.hour() > 16)) {
                    return true;
                }
                return false;
            }

            function randomNumber(min, max) {
                return Math.random() * (max - min) + min;
            }

            function randomBar(date, lastClose) {
                var open = randomNumber(lastClose * 0.95, lastClose * 1.05).toFixed(2);
                var close = randomNumber(open * 0.95, open * 1.05).toFixed(2);
                return {
                    t: date.valueOf(),
                    y: close
                };
            }

            var date = moment('Jan 01 1990', 'MMM DD YYYY');
            var now = moment();
            var data = [];
            var lessThanDay = unitLessThanDay();
            for (; data.length < 600 && date.isBefore(now); date = date.clone().add(1, unit).startOf(unit)) {
                if (outsideMarketHours(date)) {
                    if (!lessThanDay || !beforeNineThirty(date)) {
                        date = date.clone().add(date.isoWeekday() >= 5 ? 8 - date.isoWeekday() : 1, 'day');
                    }
                    if (lessThanDay) {
                        date = date.hour(9).minute(30).second(0);
                    }
                }
                data.push(randomBar(date, data.length > 0 ? data[data.length - 1].y : 30));
            }

            return data;
        }

        var ctx = document.getElementById('chart1').getContext('2d');
        ctx.canvas.width = 1000;
        ctx.canvas.height = 300;

        var color = Chart.helpers.color;
        var cfg = {
            data: {
                datasets: [{
                    label: 'CHRT - Chart.js Corporation',
                    backgroundColor: '#FF0000',
                    borderColor: '#FF0000',
                    data: generateData(),
                    type: 'line',
                    pointRadius: 0,
                    fill: false,
                    lineTension: 0,
                    borderWidth: 2
                },{
                    label: 'CHRT - Chart.js Corporation',
                    backgroundColor: '#FF0000',
                    borderColor: '#FF0000',
                    data: generateData(),
                    type: 'line',
                    pointRadius: 0,
                    fill: false,
                    lineTension: 0,
                    borderWidth: 2
                }]
            },
            options: {
                animation: {
                    duration: 0
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        distribution: 'series',
                        offset: true,
                        ticks: {
                            major: {
                                enabled: true,
                                fontStyle: 'bold'
                            },
                            source: 'data',
                            autoSkip: true,
                            autoSkipPadding: 75,
                            maxRotation: 0,
                            sampleSize: 100
                        },
                        afterBuildTicks: function(scale, ticks) {
                            var majorUnit = scale._majorUnit;
                            var firstTick = ticks[0];
                            var i, ilen, val, tick, currMajor, lastMajor;

                            val = moment(ticks[0].value);
                            if ((majorUnit === 'minute' && val.second() === 0)
                                    || (majorUnit === 'hour' && val.minute() === 0)
                                    || (majorUnit === 'day' && val.hour() === 9)
                                    || (majorUnit === 'month' && val.date() <= 3 && val.isoWeekday() === 1)
                                    || (majorUnit === 'year' && val.month() === 0)) {
                                firstTick.major = true;
                            } else {
                                firstTick.major = false;
                            }
                            lastMajor = val.get(majorUnit);

                            for (i = 1, ilen = ticks.length; i < ilen; i++) {
                                tick = ticks[i];
                                val = moment(tick.value);
                                currMajor = val.get(majorUnit);
                                tick.major = currMajor !== lastMajor;
                                lastMajor = currMajor;
                            }
                            return ticks;
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            drawBorder: false
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Closing price ($)'
                        }
                    }]
                },
                tooltips: {
                    intersect: false,
                    mode: 'index',
                    callbacks: {
                        label: function(tooltipItem, myData) {
                            var label = myData.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += parseFloat(tooltipItem.value).toFixed(2);
                            return label;
                        }
                    }
                }
            }
        };

        var chart = new Chart(ctx, cfg);

        document.getElementById('update').addEventListener('click', function() {
            var type = document.getElementById('type').value;
            var dataset = chart.config.data.datasets[0];
            dataset.type = type;
            dataset.data = generateData();
            chart.update();
        });

    </script>
</body>

</html>