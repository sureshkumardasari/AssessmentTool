{{ $widget->renderJSON() }}
<div class="dash-widget-header">
    <span>{{ $widget->headline }}</span>
</div>

<div class="dash-widget-content" style="min-height: 350px;">
    {{ $widget->render() }}
    <div id="{{ $widget->div_id }}"><!-- Fusion Charts will render here--></div>
</div>