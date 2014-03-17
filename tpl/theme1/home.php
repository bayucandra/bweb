
    <div id="nivo-wrapper">
        <div class="slider-wrapper theme-default">
            <div id="slider-item" class="nivoSlider">
                {$nivo_images}
            </div>
        </div>

    </div>
    <script type="text/javascript" src="include/nivo-slider/jquery.nivo.slider.js"></script>
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider-item').nivoSlider();
    });
    </script>
	{$random_product}