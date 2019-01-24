
<!DOCTYPE html>
<html lang="zh-Hans">

<?php
$employee_score_file = "data/output_top10_group.txt";
$file = fopen($employee_score_file, "r");
$employee_key = [];
$rankings =array();
$max_score = 0;
while (!feof($file)) {
    $contents = fgets($file);
    $items = preg_split('/\t/', $contents);
    $rankings[$items[0]] = floatval($items[1]);
    if(floatval($items[1]) > $max_score){
        $max_score = floatval($items[1]);
    }
    array_push($employee_key, $items[0]);
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Yian.Tung@TCI">
    <meta name="copyright" content="Yian.Tung@TCI">
    <title></title>
    <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <style>
        text {
            font-size: 18px;
            font-weight: bolder;
        }
    </style>
    <script>
        window.addEventListener('load',function(){
            var w = window,
                d = document,
                e = d.documentElement,
                g = d.getElementsByTagName('body')[0],
                screenWidth = w.innerWidth || e.clientWidth || g.clientWidth,
                screenHeight = w.innerHeight|| e.clientHeight|| g.clientHeight;

            var data = [
                {x:1, w:"<?php echo($rankings[$employee_key[0]]); ?>", label:"🏆<?php echo($employee_key[0]); ?>", color: "#ED553B" ,fontcolor: "#FFF"},
                {x:2, w:"<?php echo($rankings[$employee_key[1]]); ?>", label:"②<?php echo($employee_key[1]); ?>", color: "#ED713B"  ,fontcolor: "#FFF"},
                {x:3, w:"<?php echo($rankings[$employee_key[2]]); ?>", label:"③<?php echo($employee_key[2]); ?>", color: "#ED8A3B"  ,fontcolor: "#FFF"},
                {x:4, w:"<?php echo($rankings[$employee_key[3]]); ?>", label:"④<?php echo($employee_key[3]); ?>", color: "#EDAC3B"  ,fontcolor: "#000"},
                {x:5, w:"<?php echo($rankings[$employee_key[4]]); ?>", label:"⑤<?php echo($employee_key[4]); ?>", color: "#FFE100"  ,fontcolor: "#000"},
                {x:6, w:"<?php echo($rankings[$employee_key[5]]); ?>", label:"⑥<?php echo($employee_key[5]); ?>", color: "#068587"  ,fontcolor: "#FFF"},
                {x:7, w:"<?php echo($rankings[$employee_key[6]]); ?>", label:"⑦<?php echo($employee_key[6]); ?>", color: "#4FB99F"  ,fontcolor: "#fff"},
                {x:8, w:"<?php echo($rankings[$employee_key[7]]); ?>", label:"⑧<?php echo($employee_key[7]); ?>", color: "#4FCD9F"  ,fontcolor: "#000"},
                {x:9, w:"<?php echo($rankings[$employee_key[8]]); ?>", label:"⑨<?php echo($employee_key[8]); ?>", color: "#4FDC9F"  ,fontcolor: "#000"},
                {x:10, w:"<?php echo($rankings[$employee_key[9]]); ?>", label:"⑩<?php echo($employee_key[9]); ?>", color: "#4FFF9F" ,fontcolor: "#000"},
            ];

            var s = d3.select('body')
                .append('svg')
                .attr({
                    'width': screenWidth ,
                    'height': screenHeight
                });

            s.selectAll('rect')
                .data(data)
                .enter()
                .append('rect')
                .attr({
                    'fill': function(d){
                        return d.color;
                    },
                    'width':0,
                    'height': screenHeight/11 - screenHeight/110,
                    'x':0,
                    'y':function(d){
                        return (d.x-1) * screenHeight/11;
                    }
                })
                .transition(2500)
                .duration(6000)
                .attr({
                    'width':function(d){
                        return (((screenWidth)/<?php echo($max_score); ?>) * d.w );
                    }
                });

            s.selectAll('text')
                .data(data)
                .enter()
                .append('text')
                .attr({
                    'fill':function(d){
                        return d.fontcolor;
                    },
                    'x':3,
                    'y':function(d){
                        return d.x * screenHeight/11 - screenHeight/22;
                    }
                })
                .transition(2500)
                .duration(4000)
                .delay(3500)
                .attr({
                    'x':function(d){
                        if((((screenWidth)/<?php echo($max_score); ?>) * d.w ) - 175 > 0) {
                            return (((screenWidth) /<?php echo($max_score); ?>) * d.w) - 175;
                        }
                        return 0;
                    }
                })
                .tween('number',function(d){
                    var i = d3.interpolateNumber(0, d.w);
                    return function(t) {
                        this.textContent = d.label.concat(": ").concat(i(t).toFixed(1)).concat(" 分");
                    };
                });

        },false);

    </script>

</head>

<body>
</body>

</html>
