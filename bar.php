
<!DOCTYPE html>
<html lang="zh-Hans">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Yian.Tung@TCI">
    <meta name="copyright" content="Yian.Tung@TCI">
    <meta http-equiv="refresh" content="12">
    <title></title>
    <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script>
        window.addEventListener('load',function(){

            var data = [
                {x:1, w:Math.floor(Math.random()*900), label:"陈晓元", color: "#628395"},
                {x:2, w:Math.floor(Math.random()*900), label:"林锌江", color: "#96897B"},
                {x:3, w:Math.floor(Math.random()*900), label:"岳敏", color: "#DBAD6A"},
                {x:4, w:Math.floor(Math.random()*900), label:"蔡宜庭", color: "#CF995F"},
                {x:5, w:Math.floor(Math.random()*900), label:"龚建安", color: "#D0CE7C"},
                {x:6, w:Math.floor(Math.random()*900), label:"李昆达", color: "#735751"},
                {x:7, w:Math.floor(Math.random()*900), label:"张竣翔", color: "#A78A7F"},
                {x:8, w:Math.floor(Math.random()*900), label:"黄婷", color: "#E7D7C1"},
                {x:9, w:Math.floor(Math.random()*900), label:"黄正谋", color: "#BF4342"},
                {x:10, w:Math.floor(Math.random()*900), label:"蒋秀明", color: "#8C1C13"},
            ];

            var s = d3.select('body')
                .append('svg')
                .attr({
                    'width': 1000 ,
                    'height':1000
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
                    'height':50,
                    'x':0,
                    'y':function(d){
                        return (d.x-1) * 55;
                    }
                })
                .transition(3000)
                .duration(10000)
                .attr({
                    'width':function(d){
                        return d.w;
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
                        return d.x * 55 - 22;
                    },
                    fontSize: 28

                })
                .transition(3000)
                .duration(10000)
                .attr({
                    'x':function(d){
                        return d.w + 3;
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
