<!DOCTYPE html>
<meta charset="utf-8">
<style>
	body {
		width: 1060px;
		margin: 50px auto;
	}

	path {
		stroke: #fff;
	}

	path:hover {
		opacity: 0.9;
	}

	rect:hover {
		fill: blue;
	}

	.axis {
		font: 10px sans-serif;
	}

	.legend tr {
		border-bottom: 1px solid grey;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -ms-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
	}

	.legend tr:first-child {
		border-top: 1px solid grey;
	}

	.axis path,
	.axis line {
		fill: none;
		stroke: #000;
		shape-rendering: crispEdges;
	}

	.x.axis path {
		display: none;
	}

	.legend {
		margin-bottom: 76px;
		display: inline-block;
		border-collapse: collapse;
		border-spacing: 0px;
	}

	.legend td {
		padding: 4px 5px;
		vertical-align: bottom;
	}

	.legendFreq, .legendPerc {
		align: right;
		width: 50px;
	}

</style>
<body>
<div class="outgo-form">

    <?php
    use yii\widgets\ActiveForm;

    $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'date')->dropDownList(
                $years,
                ['onchange'=>'
                var self = this;
                $.post( "'.Yii::$app->urlManager->createUrl('statistic/year?year=').'"+$(this).val(), function( data ) {
                  	var fData = JSON.parse(data); 
                  	$("#dashboard").html("");
                  	dashboard("#dashboard", fData[$(self).val()]);
                });
            ',  'autofocus' => 'autodocus'])->label(Yii::t('app', 'Год')) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <div id='dashboard'>
    </div>
</div>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/4.13.1/lodash.min.js"></script>
<script>
	function dashboard(id, fData) {
        var barColor = 'steelblue';
		var labels = [];
		var colors = [];

		function segColor() {
			var letters = '0123456789ABCDEF';
			var color = '#';
			for (var i = 0; i < 6; i++) {
				color += letters[Math.floor(Math.random() * 16)];
			}
			return color;
		}

		// compute total for each slice.
		fData.forEach(function (d) {
			d.total = 0;
			for (var value in d.data) {
				d.total += d.data[value];
				labels = _.union(labels, [value]);
			}
            d.total = d.total.toFixed(2)
		});

		// function to handle histogram.
		function histoGram(fD) {

			var hG = {}, hGDim = {t: 60, r: 0, b: 30, l: 0};
			hGDim.w = screen.width*0.4 - hGDim.l - hGDim.r,
				hGDim.h = screen.height*0.5 - hGDim.t - hGDim.b;
			//create svg for histogram.
			var hGsvg = d3.select(id).append("svg")
				.attr("width", hGDim.w + hGDim.l + hGDim.r)
				.attr("height", hGDim.h + hGDim.t + hGDim.b).append("g")
				.attr("transform", "translate(" + hGDim.l + "," + hGDim.t + ")");
			// create function for x-axis mapping.
			var x = d3.scale.ordinal().rangeRoundBands([0, hGDim.w], 0.1)
				.domain(fD.map(function (d) {
					return d[0];
				}));
			// Add x-axis to the histogram svg.
			hGsvg.append("g").attr("class", "x axis")
				.attr("transform", "translate(0," + hGDim.h + ")")
				.call(d3.svg.axis().scale(x).orient("bottom"));
			// Create function for y-axis map.
            var y = d3.scale.linear().range([hGDim.h, 0])
                .domain([0, d3.max(fD, function (d) {
                    return Number(d[1]);
				})]);
			// Create bars for histogram to contain rectangles and data labels.
			var bars = hGsvg.selectAll(".bar").data(fD).enter()
				.append("g").attr("class", "bar");
			//create the rectangles.
			bars.append("rect")
				.attr("x", function (d) {
					return x(d[0]);
				})
				.attr("y", function (d) {
					return y(Number(d[1]));
				})
				.attr("width", x.rangeBand())
				.attr("height", function (d) {
					return hGDim.h - y(Number(d[1]));
				})
				.attr('fill', barColor)
				.on("mouseover", mouseover)// mouseover is defined below.
				.on("mouseout", mouseout);// mouseout is defined below.
			//Create the frequency labels above the rectangles.
			bars.append("text").text(function (d) {
				return d3.format(",")(Number(d[1]))
			})
				.attr("x", function (d) {
					return x(d[0]) + x.rangeBand() / 2;
				})
				.attr("y", function (d) {
					return y(Number(d[1])) - 5;
				})
				.attr("text-anchor", "middle")
				.attr("font-size", "12px");
			function mouseover(d) {  // utility function to be called on mouseover.
				// filter for selected slice.
				var st = fData.filter(function (s) {
						return s.slice == d[0];
					})[0],
					nD = d3.values(labels).map(function (s) {
						if (st.data[s] == undefined){
							return {type: s, data: 0};
						}
						return {type: s, data: st.data[s]};
					});
				// call update functions of pie-chart and legend.
				pC.update(nD);
				leg.update(nD);
			}

			function mouseout(d) {    // utility function to be called on mouseout.
				// reset the pie-chart and legend.
				pC.update(tF);
				leg.update(tF);
			}

			// create function to update the bars. This will be used by pie-chart.
			hG.update = function (nD, color, type) {

                // update the domain of the y-axis map to reflect change in frequencies.
				y.domain([0, d3.max(nD, function (d) {
					return Number(d[1]) || 0;
				})]);
				// Attach the new data to the bars.
				var bars = hGsvg.selectAll(".bar").data(nD);
				// transition the height and color of rectangles.
				bars.select("rect").transition().duration(500)
					.attr("y", function (d) {
						return y(Number(d[1]) || 0);
					})
					.attr("height", function (d) {
						return hGDim.h - y(Number(d[1]) || 0);
					})
					.attr("fill", color);
				// transition the frequency labels location and change value.
				bars.select("text").transition().duration(500)
					.text(function (d) {
						return d3.format(",")(Number(d[1]) || 0)
					})
					.attr("y", function (d) {
						return y(Number(d[1]) || 0) - 5;
					});
			}
			return hG;
		}

		// function to handle pieChart.
		function pieChart(pD) {
			var pC = {}, pieDim = {w: 250, h: 250};
			pieDim.r = Math.min(pieDim.w, pieDim.h) / 2;
			// create svg for pie chart.
			var piesvg = d3.select(id).append("svg")
				.attr("width", pieDim.w).attr("height", pieDim.h).append("g")
				.attr("transform", "translate(" + pieDim.w / 2 + "," + pieDim.h / 2 + ")");
			// create function to draw the arcs of the pie slices.
			var arc = d3.svg.arc().outerRadius(pieDim.r - 10).innerRadius(0);
			// create a function to compute the pie slice angles.
			var pie = d3.layout.pie().sort(null).value(function (d) {
				return d.data;
			});
			// Draw the pie slices.
			piesvg.selectAll("path").data(pie(pD)).enter().append("path").attr("d", arc)
				.each(function (d) {
					this._current = d;
				})
				.style("fill", function (d) {
					color = segColor();
					colors[d.data.type] = color;
					return color;
				})
				.on("mouseover", mouseover).on("mouseout", mouseout);
			// create function to update pie-chart. This will be used by histogram.
			pC.update = function (nD) {
				piesvg.selectAll("path").data(pie(nD)).transition().duration(500)
					.attrTween("d", arcTween);
			};
			// Utility function to be called on mouseover a pie slice.
			function mouseover(d) {
                $('table.legend').find('#' + '_' + colors[d.data.type].substr(1)).css('background', segColor());
				// call the update function of histogram with new data.
				hG.update(fData.map(function (v) {
					return [v.slice, v.data[d.data.type]];
				}), segColor(), d.data.type);
			}

			//Utility function to be called on mouseout a pie slice.
			function mouseout(d) {
                $('table.legend').find('#' + '_' + colors[d.data.type].substr(1)).css('background', '');
				// call the update function of histogram with all data.
				hG.update(fData.map(function (v) {
					return [v.slice, v.total];
				}), barColor);
			}

			// Animating the pie-slice requiring a custom function which specifies
			// how the intermediate paths should be drawn.
			function arcTween(a) {
				var i = d3.interpolate(this._current, a);
				this._current = i(0);
				return function (t) {
					return arc(i(t));
				};
			}

			return pC;
		}

		// function to handle legend.
		function legend(lD) {
			var leg = {};
			// create table for legend.
			var legend = d3.select(id).append("table").attr('class', 'legend');
			// create one row per segment.
			var tr = legend.append("tbody").selectAll("tr").data(lD).enter().append("tr").attr('id', function (d) {
                return '_' + colors[d.type].substr(1);
            });
			// create the first column for each segment.
			tr.append("td").append("svg").attr("width", '16').attr("height", '16').append("rect")
				.attr("width", '16').attr("height", '16')
				.attr("fill", function (d) {
					return colors[d.type];
				});
			// create the second column for each segment.
			tr.append("td").text(function (d) {
				return d.type;
			});
			// create the third column for each segment.
			tr.append("td").attr("class", 'legendFreq')
				.text(function (d) {
					return d3.format(",")(d.data);
				});
			// create the fourth column for each segment.
			tr.append("td").attr("class", 'legendPerc')
				.text(function (d) {
					return getLegend(d, lD);
				});
			// Utility function to be used to update the legend.
			leg.update = function (nD) {
                // update the data attached to the row elements.
                var l = legend.select("tbody").selectAll("tr").data(nD);
				// update the frequencies.
				l.select(".legendFreq").text(function (d) {
					return d3.format(",")(d.data);
				});
				// update the percentage column.
				l.select(".legendPerc").text(function (d) {
					return getLegend(d, nD);
				});
			}
			function getLegend(d, aD) { // Utility function to compute percentage.
				return d3.format("%")(d.data / d3.sum(aD.map(function (v) {
						return v.data;
					})));
			}

			return leg;
		}

		// calculate total frequency by segment for all slice.
		var tF = labels.map(function (d) {
			return {
				type: d,
				data: Math.round(d3.sum(fData.map(function (t) {
                    return t.data[d];
                })))
			};
		});
//        tF.sort(function (a, b) {
//            if (a.data > b.data) {
//                return -1;
//            }
//            if (a.data < b.data) {
//                return +1;
//            }
//            // a должно быть равным b
//            return 0;
//        });
		// calculate total frequency by slice for all segment.
		var sF = fData.map(function (d) {
			return [d.slice, d.total];
		});
		var hG = histoGram(sF), // create the histogram.
			pC = pieChart(tF), // create the pie-chart.
			leg = legend(tF);  // create the legend.
	}
</script>

<script>
	dashboard('#dashboard',<?=$Data?>);
</script>