$(document).ready(start);
url="controller/";
page="Reporte";
var horarios,holidays,licencias,fichadas;

function start(){
    horarios=JSON.parse($("#horarios").val());
    holidays=JSON.parse($("#holidays").val());
    licencias=JSON.parse($("#licencias").val());
    fichadas=JSON.parse($("#fichadas").val());
    listEmployee=JSON.parse($("#listEmployee").val());
    $.each(listEmployee.listDni,function(key,value){
        fillTableRows(parseInt(listEmployee.type),value[0],listEmployee.from,listEmployee.to,value[1],value[2]);
    });
}
function fillTableRows(type,dni,from,to,name,dependency){
    $("#contentReports").append("<div class='report' id='"+dni+"'>\
        <div class='header'>\
            <span>Nombre: <b class='name'>"+name+"</b></span>\
            <span>Dependencia: <b class='nameDependence'>"+dependency+"</b></span>\
            <span class='labelFromTo'><span class='labelFromDate'>"+from+"</span> <b>hasta</b> <span class='labelToDate'>"+to+"</span></span>\
            <input type='hidden' class='id_persona' value=''>\
        </div>\
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
    report=$("#"+dni);
    trh=report.find(".tableReport thead");
    trb=report.find(".tableReport tbody");
    ttrh=report.find(".tableTotalReport thead");
    ttrb=report.find(".tableTotalReport tbody");
    dFrom=moment(from);
    dTo=moment(to);
    l=parseInt(dTo.diff(dFrom,"days"));
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
                    <th>Trabajados</th>\
                    <th colspan='2'>Justificacion</th>\
                </tr>\
                <tr>\
                    <th style='border-left: 1px solid rgba(0,0,0,.5) !important;'>Entrada</th>\
                    <th>Salida</th>\
                    <th>Entrada</th>\
                    <th>Salida</th>\
                    <th>Minutos</th>\
                    <th>Minutos</th>\
                    <th>Horas</th>\
                    <th>Dias</th>\
                    <th>Horas</th>\
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
    
    isLicencia=licencias.find(e => e.dni === dni);
    for(i=0;i<=l;i++){
        isHoliday=holidays.find(element => element.DateTime.substr(0,10) === dFrom.format("YYYY-MM-DD"));
        hl=(isHoliday)?isHoliday.Title:'';
        cl=checkLicencia(isLicencia,dFrom);        
        switch(type){
            case 1:
                trb.append("<tr class='"+dFrom.format("YYYY-MM-DD")+"' day-week='"+(dFrom.day())+"' is-holiday='"+hl+"' is-licencia='"+cl+"'>\
                    <td>"+dFrom.format("YYYY-MM-DD")+"</td>\
                    <td>"+getIsoWeekday(dFrom.day())+"</td>\
                    <td class='t1_in'></td>\
                    <td class='t1_out'></td>\
                    <td class='t2_in'></td>\
                    <td class='t2_out'></td>\
                    <td class='minutesLate'>"+cl+"</td>\
                    <td class='minutesEarly'>"+hl+"</td>\
                    <td class='extraTime'></td>\
                    <td class='absent'></td>\
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
    getFichadasEmployee(type,dni,from,to);
}
function getFichadasEmployee(type,dni,from,to){
    report=$("#"+dni);
    fichadas=JSON.parse($("#fichadas").val());
    console.log(fichadas);
    data=fichadas[dni];
    if(data){
    //$.post(url,{page:page,action:"getFichadasEmployee",dni:dni,from:from,to:to},function(data){
        report=$("#"+dni);
        switch(type){
            case 1:
                report.find(".diasTrabajados").html(Object.keys(data).length);
                $.each(data,function(key,value){
                    if(value.length==1){
                        inOut=(value[0].tipo==1)?".t1_out":".t1_in";
                        report.find("."+key+" "+inOut).attr("full-date",value[0].fullFecha).html(value[0].hora);
                        //console.log(value[0].hora);
                    }
                    else{
                        listHour=value.map(function(value){
                            return moment(value.fullFecha);
                        });
                        max=moment.max(listHour);
                        min=moment.min(listHour);
                        report.find("."+key+" .t1_in").attr("full-date",min.format("YYYY-MM-DD HH:mm:ss")).html(min.format("HH:mm"));
                        report.find("."+key+" .t1_out").attr("full-date",max.format("YYYY-MM-DD HH:mm:ss")).html(max.format("HH:mm"));
                    }                    
                });
                break;
            case 2:
                $.each(data,function(key,value){
                    tr=report.find("."+key);
                    tr.find(".t_in_out").html("");
                    tr.find(".amount_in_out").html(value.length);
                    $.each(value,function(key,value){
                        tr.find(".t_in_out").append(value.hora+" - ");
                    });
                    //console.log(value.length);
                    if(value.length==1){
                        minMax=(value[0].tipo==1)?"max":"min";
                        tr.find(".t_in_out").attr(minMax,value[0].fullFecha);
                    }
                    else{
                        listHour=value.map(function(value){
                            return moment(value.fullFecha);
                        });
                        max=moment.max(listHour);
                        min=moment.min(listHour);
                        tr.find(".t_in_out").attr("min",min.format("YYYY-MM-DD HH:mm:ss"));
                        tr.find(".t_in_out").attr("max",max.format("YYYY-MM-DD HH:mm:ss"));
                    }
                });
                break;
        }
        
        calcHours(type,dni);
    //},"json");
    }
}
function calcHours(type,dni){
    report=$("#"+dni);
    horarios=JSON.parse($("#horarios").val());
    data=horarios.find(element => element.dni === dni)
    //$.post(url,{page:page,action:"getHorariosEmployee",dni:dni},function(data){
        //report=$("#"+dni);
        // toleranceIn=data[0].D_TOLERANCIA_ENTRADA;
        // toleranceOut=data[0].D_TOLERANCIA_SALIDA;
        var c=0,workDays=0,absentDays=0,lateDays=0,lateHours=0,daysEarly=0,hoursEarly=0,sumAllHours=0;
        switch(type){
            case 1:
                report.find(".tableReport tbody tr").each(function(){
                    extraHours=0
                    date=$(this).attr("class");
                    dayWeek=$(this).attr('day-week');
                    hourIn=$(this).find(".t1_in").attr("full-date");
                    hourOut=$(this).find(".t1_out").attr("full-date");
                    findDay=horarios.find(e => e.dni === dni && e.Day === dayWeek);
                    if(findDay){
                        if(hourIn || hourOut){
                            workDays++;
                            hourInDB=moment(date+" "+findDay.FromTime).format("YYYY-MM-DD HH:mm");
                            hourOutDB=moment(date+" "+findDay.ToTime).format("YYYY-MM-DD HH:mm");
                            hourIn=(hourIn)?hourIn.substr(0,16):moment(date+" "+findDay.FromTime).add(1,"hour").format("YYYY-MM-DD HH:mm");
                            hourOut=(hourOut)?hourOut.substr(0,16):moment(date+" "+findDay.ToTime).subtract(1,"hour").format("YYYY-MM-DD HH:mm");
                            totalHours=moment(hourOut).diff(moment(hourIn),"h")
                            totalMinutes=moment(hourOut).diff(moment(hourIn),"m");
                            if(totalMinutes>0){
                                $(this).find(".totalHours").html("<b>"+totalHours+"</b>");
                            }
                            minutesLate=moment(hourIn).diff(hourInDB,"m");
                            minutesEarly=moment(hourOutDB).diff(hourOut,"m");
                            if(minutesLate>0){
                                lateDays++;
                                lateHours=lateHours+minutesLate;
                                $(this).find(".minutesLate").html(minutesLate);
                            }
                            else{
                                extraHours=extraHours+minutesLate;
                            }
                            if(minutesEarly>0){
                                daysEarly++;
                                hoursEarly=hoursEarly+minutesEarly;
                                $(this).find(".minutesEarly").html(minutesEarly);
                            }
                            else{
                                extraHours=extraHours+minutesEarly;
                            }
                            extraHours=Math.abs(extraHours);
                            $(this).find(".extraTime").html((extraHours>20)?extraHours:"");
                            // $(this).attr("style","background-color:green;");
                            // $(this).find(".t2_in").html(findDay.FromTime);
                            // $(this).find(".t2_out").html(findDay.ToTime);
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
                                totalHours=moment(hourOut).diff(moment(hourIn),"h")
                                totalMinutes=moment(hourOut).diff(moment(hourIn),"m");
                                if(totalMinutes>0){
                                    $(this).find(".totalHours").html("<b>"+totalHours+"</b>");
                                }
                            }
                        }
                    }

                    if($(this).attr("is-holiday")){
                        $(this).find(".minutesEarly").html($(this).attr("is-holiday"));
                    }
                    if($(this).attr("is-licencia")){
                        $(this).find(".minutesLate").html($(this).attr("is-licencia"));
                    }
                });
                report.find(".diasTrabajados").html(workDays);
                report.find(".diasAusente").html(absentDays);
                report.find(".llegadasTarde").html(lateDays);
                report.find(".hsLlegadasTarde").html(lateHours);
                report.find(".salidasTemprano").html(daysEarly);
                report.find(".hsSalidasTemprano").html(hoursEarly);
                break;
            case 2:
                report.find(".tableReport tbody tr").each(function(){
                    date=$(this).attr("class");
                    dayWeek=$(this).attr('day-week');
                    hourIn=$(this).find(".t_in_out").attr("min");
                    hourOut=$(this).find(".t_in_out").attr("max");
                    isHoliday=$(this).attr("is-holiday");
                    isLicencia=$(this).attr("is-licencia");
                    findDay=horarios.find(e => e.dni === dni && e.Day === dayWeek);
                    if(findDay){
                        if((hourIn || hourOut) && (!isHoliday && !isLicencia)){
                            
                            hourInDB=moment(date+" "+findDay.FromTime).format("YYYY-MM-DD HH:mm");
                            hourOutDB=moment(date+" "+findDay.ToTime).format("YYYY-MM-DD HH:mm");
                            hourIn=(hourIn)?hourIn.substr(0,16):moment(date+" "+findDay.FromTime).add(1,"hour").format("YYYY-MM-DD HH:mm");
                            hourOut=(hourOut)?hourOut.substr(0,16):moment(date+" "+findDay.ToTime).subtract(1,"hour").format("YYYY-MM-DD HH:mm");
                            totalHours=moment(hourOut).diff(moment(hourIn),"h");
                            totalMinutes=moment(hourOut).diff(moment(hourIn),"m");
                            if(totalMinutes>0){
                                sumAllHours=sumAllHours+totalHours;
                                $(this).find(".totalHours").html("<b>"+totalHours+"</b>");
                            }
                        }
                    }
                    else{
                        if((hourIn || hourOut) && (!isHoliday && !isLicencia)){
                            workDays++;
                            if(hourIn && hourOut){
                                totalHours=moment(hourOut).diff(moment(hourIn),"h")
                                totalMinutes=moment(hourOut).diff(moment(hourIn),"m");
                                if(totalMinutes>0){
                                    sumAllHours=sumAllHours+totalHours;
                                    $(this).find(".totalHours").html("<b>"+totalHours+"</b>");
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
                report.find(".sumAllHours").html(sumAllHours);
                break;
        }
        $("#contentReports").removeAttr("style");
        $("#reportLoading").hide();
    //},"json");
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