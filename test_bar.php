
<!DOCTYPE html>
<html lang="zh-Hans">

<?php
$employee_score_file = "data/top10_employee.txt";
$file = fopen($employee_score_file, "r");
$employee_key = [];
$rankings =array();
while (!feof($file)) {
    $contents = fgets($file);
    $items = preg_split('/\t/', $contents);
    $rankings[$items[0]] = floatval($items[1]);
    array_push($employee_key, $items[0]);
    echo($items[1]);
    echo($items[0]);
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Yian.Tung@TCI">
    <meta name="copyright" content="Yian.Tung@TCI">
    <title></title>
    <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script>
        window.addEventListener('load',function(){
            var w = window,
                d = document,
                e = d.documentElement,
                g = d.getElementsByTagName('body')[0],
                screenWidth = w.innerWidth || e.clientWidth || g.clientWidth,
                screenHeight = w.innerHeight|| e.clientHeight|| g.clientHeight;

            var data = [
                {x:1, w:"<?php echo($rankings[$employee_key[0]]); ?>", label:"<?php echo($employee_key[0]); ?>", color: "#628395"},
                {x:2, w:"<?php echo($rankings[$employee_key[1]]); ?>", label:"<?php echo($employee_key[1]); ?>", color: "#96897B"},
                {x:3, w:"<?php echo($rankings[$employee_key[2]]); ?>", label:"<?php echo($employee_key[2]); ?>", color: "#DBAD6A"},
                {x:4, w:"<?php echo($rankings[$employee_key[3]]); ?>", label:"<?php echo($employee_key[3]); ?>", color: "#CF995F"},
                {x:5, w:"<?php echo($rankings[$employee_key[4]]); ?>", label:"<?php echo($employee_key[4]); ?>", color: "#D0CE7C"},
                {x:6, w:"<?php echo($rankings[$employee_key[5]]); ?>", label:"<?php echo($employee_key[5]); ?>", color: "#735751"},
                {x:7, w:"<?php echo($rankings[$employee_key[6]]); ?>", label:"<?php echo($employee_key[6]); ?>", color: "#A78A7F"},
                {x:8, w:"<?php echo($rankings[$employee_key[7]]); ?>", label:"<?php echo($employee_key[7]); ?>", color: "#E7D7C1"},
                {x:9, w:"<?php echo($rankings[$employee_key[8]]); ?>", label:"<?php echo($employee_key[8]); ?>", color: "#BF4342"},
                {x:10, w:"<?php echo($rankings[$employee_key[9]]); ?>", label:"<?php echo($employee_key[9]); ?>", color: "#8C1C13"},
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
                .duration(5000)
                .attr({
                    'width':function(d){
                        return (((screenWidth - 100)/<?php echo($rankings[$employee_key[0]]); ?>) * d.w );
                    }
                });

            s.selectAll('text')
                .data(data)
                .enter()
                .append('text')
                .text(function(d){
                    return 0  ;
                })
                .attr({
                    'fill':'#000',
                    'x':3,
                    'y':function(d){
                        return d.x * screenHeight/11 - screenHeight/22;
                    },
                    fontSize: 28

                })
                .transition(2500)
                .duration(5000)
                .attr({
                    'x':function(d){
                        return (((screenWidth - 100)/<?php echo($rankings[$employee_key[0]]); ?>) * d.w );
                    }
                })
                .tween('number',function(d){
                    var i = d3.interpolateRound(0, d.w);
                    return function(t) {
                        this.textContent = d.label.concat(": ").concat(i(t)).concat(" 分");
                    };
                });

        },false);

    </script>
</head>

<body>
</body>

</html>
