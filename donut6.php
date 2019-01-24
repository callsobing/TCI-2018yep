<?php
$employee_score_file = "data/output_question6.txt";
$file = fopen($employee_score_file, "r");
$employee_key = [];
$rankings =array();
$avg_score = 0;
$contents = fgets($file);
$items = preg_split('/\t/', $contents);
$display_string = mb_substr( $items[0],0,20,"utf-8") . "...";
$avg_score = floatval($items[1]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<canvas id="myChart"></canvas>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
<script>
    Chart.defaults.doughnutLabels = Chart.helpers.clone(Chart.defaults.doughnut);

    var helpers = Chart.helpers;
    var defaults = Chart.defaults;

    Chart.controllers.doughnutLabels = Chart.controllers.doughnut.extend({
        updateElement: function(arc, index, reset) {
            var _this = this;
            var chart = _this.chart,
                chartArea = chart.chartArea,
                opts = chart.options,
                animationOpts = opts.animation,
                arcOpts = opts.elements.arc,
                centerX = (chartArea.left + chartArea.right) / 2,
                centerY = (chartArea.top + chartArea.bottom) / 2,
                startAngle = opts.rotation, // non reset case handled later
                endAngle = opts.rotation, // non reset case handled later
                dataset = _this.getDataset(),
                circumference = reset && animationOpts.animateRotate ? 0 : arc.hidden ? 0 : _this.calculateCircumference(dataset.data[index]) * (opts.circumference / (2.0 * Math.PI)),
                innerRadius = reset && animationOpts.animateScale ? 0 : _this.innerRadius,
                outerRadius = reset && animationOpts.animateScale ? 0 : _this.outerRadius,
                custom = arc.custom || {},
                valueAtIndexOrDefault = helpers.getValueAtIndexOrDefault;

            helpers.extend(arc, {
                // Utility
                _datasetIndex: _this.index,
                _index: index,

                // Desired view properties
                _model: {
                    x: centerX + chart.offsetX,
                    y: centerY + chart.offsetY,
                    startAngle: startAngle,
                    endAngle: endAngle,
                    circumference: circumference,
                    outerRadius: outerRadius,
                    innerRadius: innerRadius,
                    label: valueAtIndexOrDefault(dataset.label, index, chart.data.labels[index]),
                    fontSize: 32
                },

                draw: function () {
                    var ctx = this._chart.ctx,
                        vm = this._view,
                        sA = vm.startAngle,
                        eA = vm.endAngle,
                        opts = this._chart.config.options;

                    var labelPos = this.tooltipPosition();
                    var segmentLabel = vm.circumference / opts.circumference * 100;

                    ctx.beginPath();

                    ctx.arc(vm.x, vm.y, vm.outerRadius, sA, eA);
                    ctx.arc(vm.x, vm.y, vm.innerRadius, eA, sA, true);

                    ctx.closePath();
                    ctx.strokeStyle = vm.borderColor;
                    ctx.lineWidth = vm.borderWidth;

                    ctx.fillStyle = vm.backgroundColor;

                    ctx.fill();
                    ctx.lineJoin = 'bevel';
                    ctx.fontSize = 32;

                    if (vm.borderWidth) {
                        ctx.stroke();
                    }

                    if (vm.circumference > 0.15) { // Trying to hide label when it doesn't fit in segment
                        ctx.beginPath();
                        ctx.font = helpers.fontString(32, opts.defaultFontStyle, opts.defaultFontFamily);
                        ctx.fillStyle = "#000000";
                        ctx.textBaseline = "top";
                        ctx.textAlign = "center";

                        // Round percentage in a way that it always adds up to 100%
                        // 			ctx.fillText(segmentLabel.toFixed(0) + "%", labelPos.x, labelPos.y);
                        ctx.fillText("", labelPos.x, labelPos.y);
                    }
                }
            });

            var model = arc._model;
            model.backgroundColor = custom.backgroundColor ? custom.backgroundColor : valueAtIndexOrDefault(dataset.backgroundColor, index, arcOpts.backgroundColor);
            model.hoverBackgroundColor = custom.hoverBackgroundColor ? custom.hoverBackgroundColor : valueAtIndexOrDefault(dataset.hoverBackgroundColor, index, arcOpts.hoverBackgroundColor);
            model.borderWidth = custom.borderWidth ? custom.borderWidth : valueAtIndexOrDefault(dataset.borderWidth, index, arcOpts.borderWidth);
            model.borderColor = custom.borderColor ? custom.borderColor : valueAtIndexOrDefault(dataset.borderColor, index, arcOpts.borderColor);

            // Set correct angles if not resetting
            if (!reset || !animationOpts.animateRotate) {
                if (index === 0) {
                    model.startAngle = opts.rotation;
                } else {
                    model.startAngle = _this.getMeta().data[index - 1]._model.endAngle;
                }

                model.endAngle = model.startAngle + model.circumference;
            }

            arc.pivot();
        }
    });

    Chart.pluginService.register({
        beforeDraw: function (chart) {
            if (chart.config.options.elements.center) {
                //Get ctx from string
                var ctx = chart.chart.ctx;

                //Get options from the center object in options
                var centerConfig = chart.config.options.elements.center;
                var fontStyle = centerConfig.fontStyle || 'Arial';
                var txt = centerConfig.text;
                var color = centerConfig.color || '#000';
                var sidePadding = centerConfig.sidePadding || 20;
                var sidePaddingCalculated = (sidePadding/100) * (chart.innerRadius * 2)
                //Start with a base font of 30px
                ctx.font = "30px " + fontStyle;

                //Get the width of the string and also the width of the element minus 10 to give it 5px side padding
                var stringWidth = ctx.measureText(txt).width;
                var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

                // Find out how much the font can grow in width.
                var widthRatio = elementWidth / stringWidth;
                var newFontSize = Math.floor(30 * widthRatio);
                var elementHeight = (chart.innerRadius * 2);

                // Pick a new font size so it will not be larger than the height of label.
                var fontSizeToUse = Math.min(newFontSize, elementHeight);

                //Set font settings to draw it correctly.
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
                var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
                ctx.font = fontSizeToUse+"px " + fontStyle;
                ctx.fillStyle = color;

                //Draw text in center
                ctx.fillText(txt, centerX, centerY);
            }
        }
    });

    var config = {
        type: 'doughnutLabels',
        data: {
            datasets: [{
                data: [<?php echo($avg_score); ?>, <?php echo(100 - $avg_score); ?>],
                backgroundColor: ["#ED553B", "#FFE100"],
                label: 'Dataset 1'
            }]
        },
        options: {
            responsive: true,
            aspectRatio: 1.3,
            title: {
                display: true,
                text: "<?php echo($display_string); ?>",
                fontSize: 20
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            elements: {
                center: {
                    text: '<?php echo($avg_score); ?>%',
                    color: '#000', //Default black
                    fontStyle: 'Helvetica', //Default Arial
                    sidePadding: 15 //Default 20 (as a percentage)
                }
            }
        }
    };

    var ctx = document.getElementById("myChart").getContext("2d");
    new Chart(ctx, config);

</script>
</body>
</html>