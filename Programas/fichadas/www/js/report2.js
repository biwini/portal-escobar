$(document).ready(start);
url="controller/";
page="Reporte";
var horarios,holidays,licencias,fichadas;

function start(){
    horarios = JSON.parse($("#horarios").val());
    holidays = JSON.parse($("#holidays").val());
    licencias = JSON.parse($("#licencias").val());
    fichadas = JSON.parse($("#fichadas").val());
    listEmployee = JSON.parse($("#listEmployee").val());
    if(parseInt(listEmployee.type)==3){
        $("#contentReports").html("<div>Desde el <b>"+listEmployee.from+"</b> hasta el <b>"+listEmployee.to+"</b></div>\
        <table class='ui celled table very compact'>\
            <thead>\
                <th>Apellido y nombre</th>\
                <th>Legajo</th>\
                <th>DNI</th>\
                <th>Dependencia</th>\
                <th>Tipo de Empleado</th>\
                <th>Horas</th>\
            </thead>\
            <tbody></tbody>\
        </table>");
    }
    $.each(listEmployee.listDni,function(key,value){
        console.log(value);
        hoursWork=(value[5]==0)?10000:value[5];
        fillTableRows(parseInt(listEmployee.type),value[0],listEmployee.from,listEmployee.to,value[1],value[2],value[3],value[4],hoursWork);
    });
    $("#downloadXls").click(exportToXls);
}
function fillTableRows(type,dni,from,to,name,dependency,employeeType,legajo,hoursWork){
    if(type!=3){
        $("#contentReports").append("<div class='report' id='"+dni+"'>\
            <table class='header'>\
                <td >Nombre: <b class='name'>"+name+"</b></td>\
                <td >Legajo: <b class='legajo'>"+((legajo!=null)?legajo:"")+"</b></td>\
                <td >Dependencia: <b class='nameDependence'>"+dependency+"</b></td>\
                <td >Tipo de Empleado: <b class='nameEmployeeType'>"+((employeeType!=null)?employeeType:"")+"</b></td>\
                <td class='labelFromTo'><span class='labelFromDate'>"+from+"</span> <b>hasta</b> <span class='labelToDate'>"+to+"</span></td>\
                <input type='hidden' class='id_persona' value=''>\
            </table>\
            <table class='ui celled table very compact tableReport' >\
                <thead>\
                </thead>\
                <tbody>\
                </tbody>\
            </table>\
            <h4>Totales</h4>\
            <table class='ui celled table very compact tableTotalReport' >\
                <thead>\
                </thead>\
            </table>\
        </div>");
    }
    else{
        
        $("#contentReports tbody").append("<tr id='"+dni+"'>\
                <td ><b class='name'>"+name+"</b></td>\
                <td >"+((legajo!=null)?legajo:"")+"</td>\
                <td >"+dni+"</td>\
                <td >"+dependency+"</td>\
                <td >"+((employeeType!=null)?employeeType:"")+"</td>\
                <td class='totalHours'></td>\
                </tr>");
    }
    report = $("#"+dni);
    trh = report.find(".tableReport thead");
    trb = report.find(".tableReport tbody");
    ttrh = report.find(".tableTotalReport thead");
    ttrb = report.find(".tableTotalReport tbody");
    dFrom = moment(from);
    dTo = moment(to);
    l = parseInt(dTo.diff(dFrom,"days"));
    switch(type){
        case 1:
            trh.html("<tr>\
                    <th rowspan='2'>Fecha</th>\
                    <th rowspan='2'>Semana</th>\
                    <th colspan='2'>Turno 1</th>\
                    <th colspan='2'>Turno 2</th>\
                    <th>LLegada tarde</th>\
                    <th>Salida temprano</th>\
                    <th>Tiempo Extra</th>\
                    <th>Ausente</th>\
                    <th colspan='3'>Trabajados</th>\
                    <th colspan='2'>Justificacion</th>\
                </tr>\
                <tr>\
                    <th style='border-left: 1px solid rgba(0,0,0,.5) !important;'>Entrada</th>\
                    <th>Salida</th>\
                    <th>Entrada</th>\
                    <th>Salida</th>\
                    <th>Horas</th>\
                    <th>Horas</th>\
                    <th>Horas</th>\
                    <th>Dias</th>\
                    <th>Normal</th>\
                    <th>Extras</th>\
                    <th>Total</th>\
                    <th>Duracion</th>\
                    <th>Hora</th>\
                </tr>");
                ttrh.html("<tr>\
                    <th>Dias trabajados</th>\
                    <th class='diasTrabajados'></th>\
                    <th>Veces llegadas tarde</th>\
                    <th class='llegadasTarde'></th>\
                    <th>Veces salida temprano</th>\
                    <th class='salidasTemprano'></th>\
                    <th>Horas salidas de trabajo</th>\
                    <th class='hsSalidasHorario'></th>\
                </tr>\
                <tr>\
                    <th>Dias ausente</th>\
                    <th class='diasAusente'></th>\
                    <th>LLegada tarde</th>\
                    <th class='hsLlegadasTarde'></th>\
                    <th>Salida temprano</th>\
                    <th class='hsSalidasTemprano'></th>\
                    <th>Horas salidas</th>\
                    <th class='horaSalidasHorario'></th>\
                </tr>");
            break;
        case 2:
            trh.html("<tr>\
                <th>Fecha</th>\
                <th>Semana</th>\
                <th>Hora</th>\
                <th>Veces</th>\
                <th>Horas trabajadas</th>\
            </tr>");
            ttrh.html("<tr>\
                <th>Horas Totales</th>\
                <th class='sumAllHours'></th>\
            </tr>");
            break;
    }
    
    isLicencia = licencias.find(e => e.dni === dni);
    for(i = 0;i <= l;i++){
        isHoliday = holidays.find(element => element.dFecha.substr(0,10) === dFrom.format("YYYY-MM-DD"));
        hl = (isHoliday)?isHoliday.cTitulo:'';
        cl = checkLicencia(isLicencia,dFrom);     
        console.log(cl)   
        switch(type){
            case 1:
                trb.append("<tr class='"+dFrom.format("YYYY-MM-DD")+"' day-week='"+(dFrom.day())+"' is-holiday='"+hl+"' is-licencia='"+cl+"'>\
                    <td>"+dFrom.format("YYYY-MM-DD")+"</td>\
                    <td class='weekDay'>"+getIsoWeekday(dFrom.day())+"</td>\
                    <td class='t1_in'>"+cl+"</td>\
                    <td class='t1_out'>"+cl+"</td>\
                    <td class='t2_in'></td>\
                    <td class='t2_out'></td>\
                    <td class='minutesLate'></td>\
                    <td class='minutesEarly'></td>\
                    <td class='extraTime'></td>\
                    <td class='absent'></td>\
                    <td class='normalHours'></td>\
                    <td class='extraHours'></td>\
                    <td class='totalHours'></td>\
                    <td></td>\
                    <td></td>\
                </tr>");
                break;
            case 2:
                trb.append("<tr class='"+dFrom.format("YYYY-MM-DD")+"' day-week='"+(dFrom.day())+"' is-holiday='"+hl+"' is-licencia='"+cl+"'>\
                    <td>"+dFrom.format("YYYY-MM-DD")+"</td>\
                    <td>"+getIsoWeekday(dFrom.day())+"</td>\
                    <td class='t_in_out'>"+cl+"</td>\
                    <td class='amount_in_out'>"+hl+"</td>\
                    <td class='totalHours'></td>\
                </tr>");
                break;
        }
        dFrom.add(1,"day");
    }
    getFichadasEmployee(type,dni,from,to,hoursWork);
}
function getFichadasEmployee(type,dni,from,to,hoursWork){
    report = $("#"+dni);
    fichadas = JSON.parse($("#fichadas").val());
    data = fichadas[dni];
    if(data){
        report = $("#"+dni);
        switch(type){
            case 1:
                report.find(".diasTrabajados").html(Object.keys(data).length);
                
                $.each(data,function(key,value){
                    if(value.length == 1){
                        inOut = (value[0].tipo == 1)?".t1_out":".t1_in";
                        report.find("."+key+" "+inOut).attr("full-date",value[0].fullFecha).html(value[0].hora);
                        
                    }
                    else{
                        listHour = value.map(function(value){
                            return moment(value.fullFecha);
                        });
                        max = moment.max(listHour);
                        min = moment.min(listHour);
                        report.find("."+key+" .t1_in").attr("full-date",min.format("YYYY-MM-DD HH:mm:ss")).html(min.format("HH:mm"));
                        report.find("."+key+" .t1_out").attr("full-date",max.format("YYYY-MM-DD HH:mm:ss")).html(max.format("HH:mm"));
                    }                    
                });
                break;
            case 2:
                $.each(data,function(key,value){
                    tr = report.find("."+key);
                    tr.find(".t_in_out").html("");
                    tr.find(".amount_in_out").html(value.length);
                    $.each(value,function(key,value){
                        tr.find(".t_in_out").append(value.hora+" - ");
                    });
                    
                    if(value.length == 1){
                        minMax = (value[0].tipo == 1)?"max":"min";
                        tr.find(".t_in_out").attr(minMax,value[0].fullFecha);
                    }
                    else{
                        listHour = value.map(function(value){
                            return moment(value.fullFecha);
                        });
                        max = moment.max(listHour);
                        min = moment.min(listHour);
                        tr.find(".t_in_out").attr("min",min.format("YYYY-MM-DD HH:mm:ss"));
                        tr.find(".t_in_out").attr("max",max.format("YYYY-MM-DD HH:mm:ss"));
                    }
                });
            break;
        }
        calcHours(type,dni,hoursWork);
    }
}
function calcHours(type,dni,hoursWork){
    switch(type){
        case 1:
            reportDetallado(dni,hoursWork);
            break;
        case 2:
            reportResumido(dni,hoursWork);
        break;
        case 3:
            reportHoras(dni,hoursWork);
        break;
    }
    $("#contentReports").removeAttr("style");
    $("#reportLoading").hide();
}
function reportDetallado(dni,hoursWork){
    report = $("#"+dni);
    horarios = JSON.parse($("#horarios").val());
    var workDays = 0,absentDays = 0,lateDays = 0,lateHours = 0,daysEarly = 0,hoursEarly = 0;
    report.find(".tableReport tbody tr").each(function(){
        extraHours = 0;
        date = $(this).attr("class");
        dayWeek = $(this).attr('day-week');
        hourIn = $(this).find(".t1_in").attr("full-date");
        hourOut = $(this).find(".t1_out").attr("full-date");
        findDay = horarios.find(e => e.dni === dni && e.idDiaSemana === dayWeek);
        
        if(findDay){
            console.log(findDay);
            if(hourIn || hourOut){
                workDays++;
                hourInDB = moment(date+" "+findDay.tEntrada).format("YYYY-MM-DD HH:mm");
                hourOutDB = moment(date+" "+findDay.tSalida).format("YYYY-MM-DD HH:mm");
                hourIn = (hourIn)?hourIn.substr(0,16):moment(date+" "+findDay.tEntrada).add(1,"hour").format("YYYY-MM-DD HH:mm");
                hourOut = (hourOut)?hourOut.substr(0,16):moment(date+" "+findDay.tSalida).subtract(1,"hour").format("YYYY-MM-DD HH:mm");
                totalHours = moment(hourOut).diff(moment(hourIn),"h");
                totalMinutes = moment(hourOut).diff(moment(hourIn),"m");
                restoHours = totalMinutes%60;
                roundTotalMinutes = totalHours*60;
                if(restoHours >= 21){
                    roundTotalMinutes=roundTotalMinutes+30;
                    if(restoHours >= 51){
                        roundTotalMinutes=roundTotalMinutes+30;
                    }
                }
                
                minutesLate = moment(hourIn).diff(hourInDB,"m");
                minutesEarly = moment(hourOutDB).diff(hourOut,"m");
                if(minutesLate > 0){
                    lateDays++;
                    lateHours = lateHours+minutesLate;
                    $(this).find(".minutesLate").html(((minutesLate-(minutesLate%60))/60)+"."+(minutesLate%60));
                }
                else{
                    extraHours = extraHours+minutesLate;
                }
                if(minutesEarly > 0){
                    daysEarly++;
                    hoursEarly = hoursEarly+minutesEarly;
                    $(this).find(".minutesEarly").html(((minutesEarly-(minutesEarly%60))/60)+"."+(minutesEarly%60));
                }
                else{
                    extraHours = extraHours+minutesEarly;
                }
                
                extraHours = Math.abs(extraHours);
                roundExtraHours = parseInt(extraHours/60)*60;
                restoExtraHours = extraHours%60;
                if(restoExtraHours >= 21){
                    roundExtraHours = roundExtraHours + 30;
                    if(restoExtraHours >= 51){
                        roundExtraHours = roundExtraHours + 30;
                    }
                }
                
                $(this).find(".extraHours").html((extraHours>=21)?(((roundExtraHours-(roundExtraHours%60))/60)+"."+(roundExtraHours%60)):"");

                if(totalMinutes >= 21){
                    calcHoursWork=hoursWork;
                    m = moment(hourOut).diff(hourIn,"m");
                        h = moment(hourOut).diff(hourIn,"h");
                        roundH = h*60;
                        restoH = m%60;
                        if(restoH >= 21){
                            roundH = roundH + 30;
                            if(restoH >= 51){
                                roundH = roundH + 30;
                            }
                        }
                        h = (roundH-(roundH%60))/60;
                        calcHoursWork = h+"."+roundH%60;
                    if(minutesLate > 0 && minutesEarly > 0){
                        m = moment(hourOut).diff(hourIn,"m");
                        h = moment(hourOut).diff(hourIn,"h");
                        roundH = h*60;
                        restoH = m%60;
                        if(restoH >= 21){
                            roundH = roundH + 30;
                            if(restoH >= 51){
                                roundH = roundH + 30;
                            }
                        }
                        h = (roundH-(roundH%60))/60;
                        calcHoursWork = h+"."+roundH%60;
                    }
                    else{
                        if(minutesLate > 0){
                            m = moment(hourOutDB).diff(hourIn,"m");
                            h = moment(hourOutDB).diff(hourIn,"h");
                            roundH = h*60;
                            restoH = m%60;
                            if(restoH >= 21){
                                roundH = roundH + 30;
                                if(restoH >= 51){
                                    roundH = roundH + 30;
                                }
                            }
                            h = (roundH-(roundH%60))/60;
                            calcHoursWork = h+"."+roundH%60;
                        }
                        else if(minutesEarly > 0){
                            m=moment(hourOut).diff(hourInDB,"m");
                            h=moment(hourOut).diff(hourInDB,"h");
                            roundH=h*60;
                            restoH=m%60;
                            if(restoH >= 21){
                                roundH = roundH + 30;
                                if(restoH >= 51){
                                    roundH = roundH + 30;
                                }
                            }
                            
                            h=(roundH-(roundH%60))/60;
                            calcHoursWork=h+"."+roundH%60;
                        }
                    }
                    console.log(calcHoursWork);
                    if(calcHoursWork>=hoursWork){
                        $(this).find(".normalHours").html("<b>"+hoursWork+".0</b>");    
                    }
                    else{
                        $(this).find(".normalHours").html("<b>"+calcHoursWork+"</b>");    
                    }
                    ht = roundH+roundExtraHours;
                    $(this).find(".totalHours").html("<b>"+((ht-(ht%60))/60)+"."+(ht%60)+"</b>");
                }
            }
            else{
                if(!$(this).attr("is-holiday")){
                    absentDays++;
                    $(this).find(".absent").html(1);
                }
            }
        }
        else{
            if(hourIn || hourOut){
                workDays++;
                if(hourIn && hourOut){
                    totalHours = moment(hourOut).diff(moment(hourIn),"h")
                    totalMinutes = moment(hourOut).diff(moment(hourIn),"m");
                    restoHours = totalMinutes%60;
                    roundTotalMinutes = totalHours*60;
                    if(restoHours >= 21){
                        roundTotalMinutes = roundTotalMinutes+30;
                        
                        if(restoHours >= 51){
                            roundTotalMinutes = roundTotalMinutes+30;
                        }
                    }
                    
                    if(totalMinutes >= 21){
                        if((roundTotalMinutes/60)>hoursWork){

                            
                            $(this).find(".normalHours").html("<b>"+hoursWork+".0</b>");    
                            $(this).find(".extraHours").html("<b>"+((((roundTotalMinutes-(roundTotalMinutes%60))/60))-hoursWork)+"."+(roundTotalMinutes%60)+"</b>");
                            $(this).find(".totalHours").html("<b>"+(((roundTotalMinutes-(roundTotalMinutes%60))/60))+"."+(roundTotalMinutes%60)+"</b>");
                        }
                        else{
                            
                            $(this).find(".normalHours").html("<b>"+(((roundTotalMinutes-(roundTotalMinutes%60))/60))+"."+(roundTotalMinutes%60)+"</b>");    
                            $(this).find(".extraHours").html("<b></b>");
                            $(this).find(".totalHours").html("<b>"+(((roundTotalMinutes-(roundTotalMinutes%60))/60))+"."+(roundTotalMinutes%60)+"</b>");
                        }
                        // $(this).find(".totalHours,.normalHours").html("<b>"+((roundTotalMinutes-(roundTotalMinutes%60))/60)+"."+(roundTotalMinutes%60)+"</b>");
                        // if(totalHours > hoursWork){
                        //     $(this).find(".normalHours").html("<b>"+hoursWork+".0</b>");
                        //     $(this).find(".extraHours").html("<b>"+(totalHours-hoursWork)+"."+roundTotalMinutes%60+"</b>");
                        // }
                        
                        
                        
                    }
                }
            }
        }

        if($(this).attr("is-holiday")){
            //$(this).find(".minutesEarly").html($(this).attr("is-holiday"));
            $(this).find(".weekDay").append("<b> (Feriado)</b>");
        }
        if($(this).attr("is-licencia")){
            $(this).find(".minutesLate").html($(this).attr("is-licencia"));
        }
    });
    report.find(".diasTrabajados").html(workDays);
    report.find(".diasAusente").html(absentDays);
    report.find(".llegadasTarde").html(lateDays);
    report.find(".hsLlegadasTarde").html((parseInt(lateHours/60))+"."+(lateHours%60));
    report.find(".salidasTemprano").html(daysEarly);
    report.find(".hsSalidasTemprano").html((parseInt(hoursEarly/60))+"."+(hoursEarly%60));
}
function reportResumido(dni,hoursWork){
    report = $("#"+dni);
    horarios = JSON.parse($("#horarios").val());
    var sumAllHours = 0;
    report.find(".tableReport tbody tr").each(function(){
        extraHours=0;
        date = $(this).attr("class");
        dayWeek = $(this).attr('day-week');
        hourIn = $(this).find(".t_in_out").attr("min");
        hourOut = $(this).find(".t_in_out").attr("max");
        isHoliday = $(this).attr("is-holiday");
        isLicencia = $(this).attr("is-licencia");
        findDay = horarios.find(e => e.dni === dni && e.idDiaSemana === dayWeek);
        if(findDay){
            if((hourIn || hourOut) && (!isHoliday && !isLicencia)){
                hourInDB = moment(date+" "+findDay.tEntrada).format("YYYY-MM-DD HH:mm");
                hourOutDB = moment(date+" "+findDay.tSalida).format("YYYY-MM-DD HH:mm");
                hourIn = (hourIn)?hourIn.substr(0,16):moment(date+" "+findDay.tEntrada).add(1,"hour").format("YYYY-MM-DD HH:mm");
                hourOut = (hourOut)?hourOut.substr(0,16):moment(date+" "+findDay.tSalida).subtract(1,"hour").format("YYYY-MM-DD HH:mm");
                totalHours = moment(hourOut).diff(moment(hourIn),"h");
                totalMinutes = moment(hourOut).diff(moment(hourIn),"m");
                restoHours = totalMinutes%60;
                roundTotalMinutes = totalHours*60;
                if(restoHours >= 21){
                    roundTotalMinutes = roundTotalMinutes+30;
                    if(restoHours >= 51){
                        roundTotalMinutes = roundTotalMinutes+30;
                    }
                }
                minutesLate = moment(hourIn).diff(hourInDB,"m");
                minutesEarly = moment(hourOutDB).diff(hourOut,"m");
                if(minutesLate < 0){
                    extraHours = extraHours+minutesLate;
                }
                if(minutesEarly < 0){
                    extraHours = extraHours+minutesEarly;
                }
                extraHours = Math.abs(extraHours);
                roundExtraHours = parseInt(extraHours/60)*60;
                restoExtraHours = extraHours%60;
                if(restoExtraHours >= 21){
                    roundExtraHours = roundExtraHours + 30;
                    if(restoExtraHours >= 51){
                        roundExtraHours = roundExtraHours + 30;
                    }
                }


                if(totalMinutes >= 21){
                    calcHoursWork=hoursWork;
                    if(minutesLate > 0 && minutesEarly > 0){
                        m = moment(hourOut).diff(hourIn,"m");
                        h = moment(hourOut).diff(hourIn,"h");
                        roundH = h*60;
                        restoH = m%60;
                        if(restoH >= 21){
                            roundH = roundH + 30;
                            if(restoH >= 51){
                                roundH = roundH + 30;
                            }
                        }
                        h = (roundH-(roundH%60))/60;
                        calcHoursWork = h+"."+roundH%60;
                    }
                    else{
                        if(minutesLate > 0){
                            m = moment(hourOutDB).diff(hourIn,"m");
                            h = moment(hourOutDB).diff(hourIn,"h");
                            roundH = h*60;
                            restoH = m%60;
                            if(restoH >= 21){
                                roundH = roundH + 30;
                                if(restoH >= 51){
                                    roundH = roundH + 30;
                                }
                            }
                            h = (roundH-(roundH%60))/60;
                            calcHoursWork = h+"."+roundH%60;
                        }
                        else if(minutesEarly > 0){
                            m=moment(hourOut).diff(hourInDB,"m");
                            h=moment(hourOut).diff(hourInDB,"h");
                            roundH=h*60;
                            restoH=m%60;
                            if(restoH >= 21){
                                roundH = roundH + 30;
                                if(restoH >= 51){
                                    roundH = roundH + 30;
                                }
                            }
                            
                            h=(roundH-(roundH%60))/60;
                            calcHoursWork=h+"."+roundH%60;
                        }
                    }



                    ht = roundH+roundExtraHours;
                    sumAllHours = sumAllHours + ht;
                    $(this).find(".totalHours").html("<b>"+((ht-(ht%60))/60)+"."+(ht%60)+"</b>");
                    // total = ((roundTotalMinutes-(roundTotalMinutes%60))/60)+((roundTotalMinutes%60>0)?"."+(roundTotalMinutes%60):"");
                    // sumAllHours = sumAllHours+roundTotalMinutes;
                    // $(this).find(".totalHours").html("<b>"+total+"</b>");
                }
            }
        }
        else{
            if((hourIn || hourOut) && (!isHoliday && !isLicencia)){
                if(hourIn && hourOut){
                    totalHours = moment(hourOut).diff(moment(hourIn),"h")
                    totalMinutes = moment(hourOut).diff(moment(hourIn),"m");
                    restoHours = totalMinutes%60;
                    roundTotalMinutes = totalHours*60;
                    if(restoHours >= 21){
                        roundTotalMinutes = roundTotalMinutes+30;
                        if(restoHours >= 51){
                            roundTotalMinutes = roundTotalMinutes+30;
                        }
                    }
                    if(totalMinutes >= 21){
                        total = ((roundTotalMinutes-(roundTotalMinutes%60))/60)+"."+(roundTotalMinutes%60);
                        sumAllHours = sumAllHours+roundTotalMinutes;
                        $(this).find(".totalHours").html("<b>"+total+"</b>");
                    }
                }
            }
        }
        if(isHoliday || isLicencia){
            $(this).find(".amount_in_out").html("");
            $(this).find(".t_in_out").html("");
            if(isHoliday){
                $(this).find(".amount_in_out").html(isHoliday);
            }
            if(isLicencia){
                $(this).find(".t_in_out").html(isLicencia);
            }
        }
    });
    report.find(".sumAllHours").html("<b>"+((sumAllHours-(sumAllHours%60))/60)+"."+(sumAllHours%60)+"</b>");
}
function reportHoras(dni,hoursWork){
    report = $("#"+dni);
    horarios = JSON.parse($("#horarios").val());
    var sumAllHours = 0;
    isLicencia = licencias.find(e => e.dni === dni);
    fichadas = JSON.parse($("#fichadas").val());
    data = fichadas[dni];
    $.each(data,function(key,value){
        var min = 0,max = 0;
        if(value.length == 1){
            if(value[0].tipo == 1){
                max = value[0].fullFecha;
            }
            else{
                min = value[0].fullFecha;
            }
        }
        else{
            listHour = value.map(function(value){
                return moment(value.fullFecha);
            });
            max = moment.max(listHour).format("YYYY-MM-DD HH:mm");
            min = moment.min(listHour).format("YYYY-MM-DD HH:mm");
        }
        date = moment(key);
        dayWeek = date.day();
        hourIn = min;
        hourOut = max;
        isHoliday = holidays.find(element => element.dFecha.substr(0,10) === date.format("YYYY-MM-DD"));
        findDay = horarios.find(e => e.dni === dni && e.idDiaSemana === dayWeek.toString());
        if(findDay){
            if((hourIn || hourOut) && (!isHoliday && !isLicencia)){
                extraHours=0;
                hourInDB = moment(date.format("YYYY-MM-DD")+" "+findDay.tEntrada).format("YYYY-MM-DD HH:mm");
                hourOutDB = moment(date.format("YYYY-MM-DD")+" "+findDay.tSalida).format("YYYY-MM-DD HH:mm");
                
                hourIn = (hourIn)?hourIn.substr(0,16):moment(date.format("YYYY-MM-DD")+" "+findDay.tEntrada).add(1,"hour").format("YYYY-MM-DD HH:mm");
                hourOut = (hourOut)?hourOut.substr(0,16):moment(date.format("YYYY-MM-DD")+" "+findDay.tSalida).subtract(1,"hour").format("YYYY-MM-DD HH:mm");
                
                totalHours = moment(hourOut).diff(moment(hourIn),"h");
                totalMinutes = moment(hourOut).diff(moment(hourIn),"m");
                restoHours = totalMinutes%60;
                roundTotalMinutes = totalHours*60;
                if(restoHours >= 21){
                    roundTotalMinutes = roundTotalMinutes+30;
                    if(restoHours >= 51){
                        roundTotalMinutes = roundTotalMinutes+30;
                    }
                }
                
                minutesLate = moment(hourIn).diff(hourInDB,"m");
                minutesEarly = moment(hourOutDB).diff(hourOut,"m");
                if(minutesLate < 0){
                    extraHours = extraHours+minutesLate;
                }
                if(minutesEarly < 0){
                    extraHours = extraHours+minutesEarly;
                }
                extraHours = Math.abs(extraHours);
                roundExtraHours = parseInt(extraHours/60)*60;
                restoExtraHours = extraHours%60;
                if(restoExtraHours >= 21){
                    roundExtraHours = roundExtraHours + 30;
                    if(restoExtraHours >= 51){
                        roundExtraHours = roundExtraHours + 30;
                    }
                }
                
                if(totalMinutes >= 21){
                    calcHoursWork=hoursWork;
                    if(minutesLate > 0 && minutesEarly > 0){
                        m = moment(hourOut).diff(hourIn,"m");
                        h = moment(hourOut).diff(hourIn,"h");
                        roundH = h*60;
                        restoH = m%60;
                        if(restoH >= 21){
                            roundH = roundH + 30;
                            if(restoH >= 51){
                                roundH = roundH + 30;
                            }
                        }
                        h = (roundH-(roundH%60))/60;
                        calcHoursWork = h+"."+roundH%60;
                    }
                    else{
                        if(minutesLate > 0){
                            m = moment(hourOutDB).diff(hourIn,"m");
                            h = moment(hourOutDB).diff(hourIn,"h");
                            roundH = h*60;
                            restoH = m%60;
                            if(restoH >= 21){
                                roundH = roundH + 30;
                                if(restoH >= 51){
                                    roundH = roundH + 30;
                                }
                            }
                            h = (roundH-(roundH%60))/60;
                            calcHoursWork = h+"."+roundH%60;
                        }
                        else if(minutesEarly > 0){
                            m=moment(hourOut).diff(hourInDB,"m");
                            h=moment(hourOut).diff(hourInDB,"h");
                            roundH=h*60;
                            restoH=m%60;
                            if(restoH >= 21){
                                roundH = roundH + 30;
                                if(restoH >= 51){
                                    roundH = roundH + 30;
                                }
                            }
                            
                            h=(roundH-(roundH%60))/60;
                            calcHoursWork=h+"."+roundH%60;
                        }
                    }



                    ht = roundH+roundExtraHours;
                    
                    sumAllHours = sumAllHours + ht;
                }
            }
        }
        else{
            if((hourIn || hourOut) && (!isHoliday && !isLicencia)){
                if(hourIn && hourOut){
                    totalHours = moment(hourOut).diff(moment(hourIn),"h")
                    totalMinutes = moment(hourOut).diff(moment(hourIn),"m");
                    restoHours = totalMinutes%60;
                    roundTotalMinutes = totalHours*60;
                    if(restoHours >= 21){
                        roundTotalMinutes = roundTotalMinutes+30;
                        if(restoHours >= 51){
                            roundTotalMinutes = roundTotalMinutes+30;
                        }
                    }
                    if(totalMinutes >= 21){
                        sumAllHours = sumAllHours+roundTotalMinutes;
                    }

                }
            }
        }
        

    });
    report.find(".totalHours").html("<b>"+((sumAllHours-(sumAllHours%60))/60)+"."+(sumAllHours%60)+"</b>");
}





function getIsoWeekday(d){
    switch(d){
        case 0:return "Dom"; break;
        case 1:return "Lun"; break;
        case 2:return "Mar"; break;
        case 3:return "Mie"; break;
        case 4:return "Jue"; break;
        case 5:return "Vie"; break;
        case 6:return "Sab"; break;
    }
}
function checkLicencia(isLicencia,date){
    r="";
    if(isLicencia){
        c=date.isBetween(isLicencia.fecha_desde,isLicencia.fecha_hasta,undefined, '[]');
        if(c){
            r=isLicencia.descripcion;
        }
    }
    return r;
}

function generateExcel(el) {
    // var clon = $('#').clone();
    let clon = $('#contentReports').clone();
    clon.find('tr td').css({'border':'1px solid'});
    clon.find('tr th').css({'border':'1px solid'});
    let html = clon.html();
    console.log(html)

    // return false;

    // var html = $('#contentReports').html();

    //add more symbols if needed...
    while (html.indexOf('á') != -1) html = html.replace(/á/g, '&aacute;');
    while (html.indexOf('é') != -1) html = html.replace(/é/g, '&eacute;');
    while (html.indexOf('í') != -1) html = html.replace(/í/g, '&iacute;');
    while (html.indexOf('ó') != -1) html = html.replace(/ó/g, '&oacute;');
    while (html.indexOf('ú') != -1) html = html.replace(/ú/g, '&uacute;');
    while (html.indexOf('º') != -1) html = html.replace(/º/g, '&ordm;');
    html = html.replace(/<td>/g, "<td>&nbsp;");

    // myWindow = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
    let uri = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);

    let downloadLink = document.createElement("a");
    downloadLink.href = uri;
    downloadLink.download = "Reportes.xls";

    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);

}

function exportToXls(){
    generateExcel();

    return false;
}