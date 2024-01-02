<?php
$page_title = "Sales";
require_once '../layout/header.php';
?>

<style>
	.sales-report-title {
		display: grid;
		place-items: center;
		font-size: 36px;
		margin: 15px 0;
	}

	.warning-msg {
		color: #e94844;
		display: none;
	}

	.range-container {
		display: flex;
		column-gap: 15px;
		align-items: center;
	}

	.date-input {
		/* font-size: 15px; */
		padding: 3px;
	}

	.separator {
		font-size: 28px;
		line-height: 0;
	}

	.refresh-btn {
		background-color: white;
		outline: none;
		border: 1px solid gray;
		border-radius: 3px;
		padding: 5px;
	}

	.refresh-icon {
		display: flex;
		width: 20px;
		height: 20px;
	}

	.loader {
		height: inherit;
		display: grid;
		place-items: center;
	}

	.loader-icon {
		height: 65px;
		width: 65px;
		animation: spin 2s linear infinite;
	}

	.chart-container {
		height: calc(100dvh - 315px);
	}

	#chartdiv {
		width: 100%;
		height: inherit;
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}
</style>

<div>
	<div class="sales-report-title">Sales Report</div>
	<h3>From</h3>
	<p class="warning-msg"><i>Start Date</i> must be greater than <i>End Date</i></p>
	<div class="range-container">
		<input type="date" name="start" class="date-input" id="start" placeholder="dd-mm-yyyy" min="2023-01-01" max="2030-12-31" format="dd-MM-yyyy">
		<span class="separator">-</span>
		<input type="date" name="end" class="date-input" id="end" placeholder="dd-mm-yyyy" min="2023-01-01" max="2030-12-31" format="dd-MM-yyyy">
		<button type="button" class=" refresh-btn" onclick="refresh()">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="refresh-icon">
				<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
			</svg>
		</button>
	</div>
	<hr>
	<div class="chart-container">
		<div class="loader">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="loader-icon">
				<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
			</svg>
		</div>
		<div id="chartdiv"></div>
	</div>
	<!-- </div> -->

	<!-- Resources -->
	<script src="../../resources/datatables/jquery.dataTables.min.js"></script>
	<script src="../../resources/datatables/angular-datatables.min.js"></script>
	<script src="../../resources/datatables/angular-datatables.bootstrap.min.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

	<!-- Chart code -->
	<script>
		const api_url = "../api/dashboard.php?service=sales_by_date";
		const startDate = document.querySelector("#start");
		const endDate = document.querySelector("#end");
		const warningMsg = document.querySelector(".warning-msg");
		const loader = document.querySelector(".loader");
		const chartDiv = document.querySelector("#chartdiv");

		// startDate.value = `${endDate.value.slice(0, -2)}01`;
		startDate.value = '2023-12-01';
		endDate.value = new Date().toISOString().split('T')[0];
		// endDate.value = '2024-01-31';

		const root = am5.Root.new("chartdiv");
		root.setThemes([
			am5themes_Animated.new(root)
		]);

		const chart = root.container.children.push(am5xy.XYChart.new(root, {
			panX: true,
			panY: true,
			wheelX: "panX",
			wheelY: "zoomX",
			pinchZoomX: true,
			paddingLeft: 0,
			paddingRight: 1
		}));

		let xAxis;
		let series;

		am5.ready(function() {
			const cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
			cursor.lineY.set("visible", false);


			// Create axes
			// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
			const xRenderer = am5xy.AxisRendererX.new(root, {
				minGridDistance: 30,
				minorGridEnabled: true
			});

			xRenderer.labels.template.setAll({
				rotation: -90,
				centerY: am5.p50,
				centerX: am5.p100,
				paddingRight: 15
			});

			xRenderer.grid.template.setAll({
				location: 1
			})

			xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
				maxDeviation: 0.3,
				categoryField: "date",
				renderer: xRenderer,
				tooltip: am5.Tooltip.new(root, {})
			}));

			const yRenderer = am5xy.AxisRendererY.new(root, {
				strokeOpacity: 0.1
			})

			const yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
				maxDeviation: 0.3,
				renderer: yRenderer
			}));

			// Create series
			series = chart.series.push(am5xy.ColumnSeries.new(root, {
				name: "Series 1",
				xAxis: xAxis,
				yAxis: yAxis,
				valueYField: "total",
				sequencedInterpolation: true,
				categoryXField: "date",
				tooltip: am5.Tooltip.new(root, {
					labelText: "{valueY}"
				})
			}));

			series.columns.template.setAll({
				cornerRadiusTL: 5,
				cornerRadiusTR: 5,
				strokeOpacity: 0
			});
			series.columns.template.adapters.add("fill", function(fill, target) {
				return chart.get("colors").getIndex(series.columns.indexOf(target));
			});

			series.columns.template.adapters.add("stroke", function(stroke, target) {
				return chart.get("colors").getIndex(series.columns.indexOf(target));
			});

			series.appear(1000);
			chart.appear(1000, 100);
		});

		const drawChart = (data = []) => {
			loader.style.display = "none";
			chartDiv.style.display = "block";

			xAxis.data.setAll(data);
			series.data.setAll(data);
		}

		const refresh = (s = startDate.value, e = endDate.value) => {
			if (parseInt(startDate.value.replaceAll("-", "")) > parseInt(endDate.value.replaceAll("-", ""))) {
				warningMsg.style.display = "block";
			} else {
				warningMsg.style.display = "none";
				loader.style.display = "block";

				fetch(`${api_url}&start=${s}&end=${e}`).then(response => response.json()).then(drawChart)
			}
		}

		refresh()
	</script>

	<?php require_once '../layout/footer.php'; ?>