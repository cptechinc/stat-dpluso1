$(function() {
    let xlabelformat = 'MMM';

    new Morris.Line({
        element: 'yoy-chart',
        data: yoyData,
        // The name of the data record attribute that contains x-values.
        xkey: 'date',
        dateFormat: function (d) {
            var ds = new Date(d);
            return moment(ds).format('MMMM');
        },
        xLabels: 'month',
        // A list of names of data record attributes that contain y-values.
        ykeys: yoyYearsKeys,
        // Labels for the ykeys -- will be displayed when you hover over the
        // chart.
        labels: yoyYearsKeys,
        xLabelFormat: function (x) {
            return moment(x).format(xlabelformat);
        },
        yLabelFormat: function (y) { 
            if (y == undefined) {
                return 'N/A';
            }
            return "$ "+ y.formatMoney(); 
        },
        lineColors: yoyColors,
        hoverCallback: function (index, options, content, row) {
            console.log(row);
            let html = '<div class="morris-hover-row-label">' + row.month.charAt(0).toUpperCase() + row.month.slice(1); + '</div>';
            console.log(row);
            console.log(options);
            console.log('');
            yoyYearsKeys.forEach((year, index) => {
                var yearSpan = '<span class="badge btn-xs" style="background: '+ options.lineColors[index] +'">' + year + '</span>';
                var dollarText = options.yLabelFormat(row[year]);
                html += `<div class="morris-hover-point" style="text-align:left">${yearSpan}: &nbsp; <span class="pull-right">${dollarText}</span></div>`;
            });
            return html;
        }
    });
});