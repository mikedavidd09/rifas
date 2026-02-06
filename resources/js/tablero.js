
/*
 * var graph_data = [{time:"2013-04-03", value: "4"}];

 var shoreham_g = Morris.Line({
 element: 'line-example',
 data: graph_data,
 xkey: 'time',
 ykeys: ['value'],
 labels: ['wind'],
 gridEnabled: false
 });

 var pusher = new Pusher('xxxx');
 var channel_weather = pusher.subscribe('weather-channel');

 channel_weather.bind('new_shoreham_event', function(data){
 graph_data.push({
 fecha_desembolso: data.fecha_desembolso,
 clientes: data.clientes
 });

 shoreham_g.setData(graph_data);
 });
 *
 *
 * */
Date.prototype.getWeek = function() {
    var date = new Date(this.getTime());
    date.setHours(0, 0, 0, 0);
    // Thursday in current week decides the year.
    date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
    // January 4 is always in week 1.
    var week1 = new Date(date.getFullYear(), 0, 4);
    // Adjust to Thursday in week 1 and count number of weeks from date to week1.
    return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000
            - 3 + (week1.getDay() + 6) % 7) / 7);
};
var date = new Date();
var primerDia = new Date(date.getFullYear(), date.getMonth(), 1);
var ultimoDia = new Date(date.getFullYear(), date.getMonth() + 1, 0);
primerDia = primerDia.toISOString().substr(0, 10);
ultimoDia = ultimoDia.toISOString().substr(0, 10);

var primer = new Date(date.getFullYear(), 0, (date.getWeek() - 1) * 7 + 1);
var ultimo = new Date(date.getFullYear(), 0, (date.getWeek() - 1) * 7 + 7);
primer = primer.toISOString().substr(0, 10);
ultimo = ultimo.toISOString().substr(0, 10);

var date_start;
var date_end;
var d_start = $('#fecha_bengin');
var d_end = $('#fecha_end');
var rage_date = $('#range_date').val();
date_start = primerDia;
date_end   = ultimoDia;

getData();
$('#ok').on('click',getData);
$('#range_date').change(function() {
    rage_date = $(this).val();
    if(rage_date=="range_custom"){
        $('#getRangeCustom').modal("show");
    }
    if(rage_date == 'mes'){
        date_start = primerDia;
        date_end   = ultimoDia;
        getData();
    }
    if(rage_date == 'semana'){
        date_start = primer;
        date_end   = ultimo;
        getData();
    }
});
function getData() {
    console.log(date_start+' '+date_end);
    if(d_start.val() != ''){
        date_start = d_start.val();
        date_end   = d_end.val();
    }
    $.ajax({
        url: 'index.php?controller=cliente&action=getClintesResumenBydate&date_start='+date_start+'&date_end='+date_end,
        success: function (data) {
            data = JSON.parse(data);
            chartsBar.setData(data);
            chartsLinea.setData(data);
        }
    });
    date_start = d_start.val('');
    date_end   = d_end.val('');
}


//setInterval( function(){$('#card').load();}, 1000 );

var chartsLinea = new  Morris.Line({
    // ID of the element in which to draw the chart.
    element: 'myfirstchart',
    // Chart data records -- each entry in this array corresponds to a point on
    // the chart.
    // The name of the data record attribute that contains x-values.
    xkey: 'fecha_desembolso',
    // A list of names of data record attributes that contain y-values.
    ykeys: ['clientes'],
    // Labels for the ykeys -- will be displayed when you hover over the
    // chart.
    labels: ['Desembolsos'],
    lineColors: ['#167f39','#990000', '#99'],
    resize: true
});

var chartsBar = new Morris.Bar({
    // ID of the element in which to draw the chart.
    element: 'test',
    // Chart data records -- each entry in this array corresponds to a point on
    // the chart.
    // The name of the data record attribute that contains x-values.
    xkey: 'fecha_desembolso',
    // A list of names of data record attributes that contain y-values.
    ykeys: ['clientes'],
    // Labels for the ykeys -- will be displayed when you hover over the
    // chart.
    labels: ['Desembolsos'],
    lineColors: ['#167f39','#990000', '#99'],
    resize: true
});


var $input = $( '.datepicker' ).pickadate({
    format: 'dd-mmmm-yyyy',
    formatSubmit: 'yyyy/mm/dd',
    // min: [2015, 7, 14],
    container: '#container-picker',
    //editable: true,
    closeOnSelect: false,
    closeOnClear: false,
});
var picker = $input.pickadate('picker');
// picker.set('select', '14 October, 2014')
// picker.open()

// $('button').on('click', function() {
//     picker.set('disable', true);
// });

