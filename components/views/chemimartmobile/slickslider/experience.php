<div class="exp_cont">

				<div class="col-sm-12 worker wa col-xs-10">
					<div class="col-sm-14 explist">

						<ul>
							<li><span> University of Pennsylvania, USA</span></li>
							<li><span> University of Oxford, UK</span></li>
							<li><span> University of Bristol, UK</span></li>
							<li><span> Lomonosov Moscow State University, Russia</span></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-12 worker col-xs-10">
					<div class="col-sm-14 explist">
				
						<ul>
							<li><span> Helmholz-Centre for Infection Research, Germany</span></li>
							<li><span> ETH Zürich, Institute for Pharm Sciences, Switzerland</span></li>
							<li><span> University of Tübingen, Germany</span></li>
							<li><span> Technische Universität Berlin, Germany</span></li>
						</ul>
					</div>
					</div>
				</div>

		
<script>
jQuery(document).ready(function() {
			$('.exp_cont').slick({
		slidesToShow: 2,
		arrows: true,
		dots: false,
		infinite: true,
		responsive: [{
			breakpoint: 768,
			settings: {
				arrows: true,
			}
		}]
	});
	$(".lab_sl").on('afterChange', function(event, slick, currentSlide){
		$("#cp").text(currentSlide + 1);
	});
	var slickk=$('.lab_sl');
$('.vsego').html( slickk.slick("getSlick").slideCount);
});
</script>
