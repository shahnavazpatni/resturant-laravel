<style>
    @foreach($sliders as $key=>$slider)
    .owl-carousel .owl-wrapper, .owl-carousel .owl-item:nth-child({{$key + 1}}) .item{
        background: url({{asset('upload/sliders/'.$slider->image)}});
        background-size: cover;
    }
    @endforeach
</style>