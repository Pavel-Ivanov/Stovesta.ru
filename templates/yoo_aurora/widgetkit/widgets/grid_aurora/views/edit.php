<div class="uk-grid uk-grid-divider uk-form uk-form-horizontal" data-uk-grid-margin>
    <div class="uk-width-medium-1-4">

        <div class="wk-panel-marginless">
            <ul class="uk-nav uk-nav-side" data-uk-switcher="{connect:'#nav-content'}">
                <li><a href="">Columns</a></li>
                <li><a href="">Content</a></li>
                <li><a href="">General</a></li>
            </ul>
        </div>

    </div>
    <div class="uk-width-medium-3-4">

        <ul id="nav-content" class="uk-switcher">
            <li>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-columns">Phone Portrait</label>
                    <div class="uk-form-controls">
                        <select id="wk-columns" class="uk-form-width-medium" ng-model="widget.data['columns']">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-columns-small">Phone Landscape</label>

                    <div class="uk-form-controls">
                        <select id="wk-columns-small" class="uk-form-width-medium" ng-model="widget.data['columns_small']">
                            <option value="0">Inherit</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-columns-medium">Tablet</label>

                    <div class="uk-form-controls">
                        <select id="wk-columns-medium" class="uk-form-width-medium" ng-model="widget.data['columns_medium']">
                            <option value="0">Inherit</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-columns-large">Desktop</label>
                    <div class="uk-form-controls">
                        <select id="wk-columns-large" class="uk-form-width-medium" ng-model="widget.data['columns_large']">
                            <option value="0">Inherit</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-columns-xlarge">Large Screens</label>
                    <div class="uk-form-controls">
                        <select id="wk-columns-xlarge" class="uk-form-width-medium" ng-model="widget.data['columns_xlarge']">
                            <option value="0">Inherit</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-gutter">Gutter</label>
                    <div class="uk-form-controls">
                        <select id="wk-gutter" class="uk-form-width-medium" ng-model="widget.data['gutter']">
                            <option value="default">Default</option>
                            <option value="small">Small</option>
                            <option value="none">None</option>
                        </select>
                    </div>
                </div>

            </li>
            <li>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-panel">Panel</label>
                    <div class="uk-form-controls">
                        <select id="wk-panel" class="uk-form-width-medium" ng-model="widget.data['panel']">
                            <option value="blank">Blank</option>
                            <option value="box">Box</option>
                            <option value="primary">Box Primary</option>
                            <option value="secondary">Box Secondary</option>
                            <option value="header">Header</option>
                            <option value="space">Space</option>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-animation">Animation</label>
                    <div class="uk-form-controls">
                        <select id="wk-animation" class="uk-form-width-medium" ng-model="widget.data['animation']">
                            <option value="none">None</option>
                            <option value="fade">Fade</option>
                            <option value="scale-up">Scale Up</option>
                            <option value="scale-down">Scale Down</option>
                            <option value="slide-top">Slide Top</option>
                            <option value="slide-bottom">Slide Bottom</option>
                            <option value="slide-left">Slide Left</option>
                            <option value="slide-right">Slide Right</option>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-text-align">Text Align</label>
                    <div class="uk-form-controls">
                        <select id="wk-text-align" class="uk-form-width-medium" ng-model="widget.data['text_align']">
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="center">Center</option>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-title-size">Title Size</label>
                    <div class="uk-form-controls">
                        <select id="wk-title-size" class="uk-form-width-medium" ng-model="widget.data['title_size']">
                            <option value="panel">Default</option>
                            <option value="h1">H1</option>
                            <option value="h2">H2</option>
                            <option value="h3">H3</option>
                            <option value="h4">H4</option>
                            <option value="large">Extra Large</option>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-media-align">Media Align</label>
                    <div class="uk-form-controls">
                        <select id="wk-media-align" class="uk-form-width-medium" ng-model="widget.data['media_align']">
                            <option value="teaser">Teaser</option>
                            <option value="top">Above Title</option>
                            <option value="bottom">Below Title</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                        </select>
                        <p class="uk-form-controls-condensed" ng-if="widget.data.media_align == 'left' || widget.data.media_align == 'right'">
                            <label>
                                <select class="uk-form-width-mini" ng-model="widget.data['media_width']">
                                    <option value="1-5">20%</option>
                                    <option value="1-4">25%</option>
                                    <option value="3-10">30%</option>
                                    <option value="1-3">33%</option>
                                    <option value="2-5">40%</option>
                                    <option value="1-2">50%</option>
                                </select>
                                Width
                            </label>
                        </p>
                        <p class="uk-form-controls-condensed" ng-if="widget.data.media_align == 'left' || widget.data.media_align == 'right'">
                            <label>
                                <select class="uk-form-width-small" ng-model="widget.data['media_breakpoint']">
                                    <option value="small">Phone Landscape</option>
                                    <option value="medium">Tablet</option>
                                    <option value="large">Desktop</option>
                                    <option value="xlarge">Large Screens</option>
                                </select>
                                Breakpoint
                            </label>
                        </p>
                        <p class="uk-form-controls-condensed" ng-if="widget.data.media_align == 'left' || widget.data.media_align == 'right'">
                            <label><input type="checkbox" ng-model="widget.data['content_align']"> Vertical centered.</label>
                        </p>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-media-border">Media Border</label>
                    <div class="uk-form-controls">
                        <select id="wk-media-border" class="uk-form-width-medium" ng-model="widget.data['media_border']">
                            <option value="none">None</option>
                            <option value="circle">Circle</option>
                            <option value="rounded">Rounded</option>
                        </select>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-link-style">Link</label>
                    <div class="uk-form-controls">
                        <select id="wk-link-style" class="uk-form-width-medium" ng-model="widget.data['link_style']">
                            <option value="text">Text</option>
                            <option value="button">Button</option>
                            <option value="primary">Button Primary</option>
                            <option value="button-large">Button Large</option>
                            <option value="primary-large">Button Large Primary</option>
                        </select>
                    </div>
                </div>

            </li>
            <li>

                <div class="uk-form-row">
                    <span class="uk-form-label">Display</span>
                    <div class="uk-form-controls uk-form-controls-text">
                        <p class="uk-form-controls-condensed" class="uk-form-controls-condensed">
                            <label><input type="checkbox" ng-model="widget.data['title']"> Show title</label>
                        </p>
                        <p class="uk-form-controls-condensed" class="uk-form-controls-condensed">
                            <label><input type="checkbox" ng-model="widget.data['media']"> Show media</label>
                        </p>
                        <p class="uk-form-controls-condensed" class="uk-form-controls-condensed">
                            <label><input type="checkbox" ng-model="widget.data['content']"> Show content</label>
                        </p>
                        <p class="uk-form-controls-condensed" class="uk-form-controls-condensed">
                            <label><input type="checkbox" ng-model="widget.data['link']"> Show link</label>
                        </p>
                        <p class="uk-form-controls-condensed" class="uk-form-controls-condensed">
                            <label><input type="checkbox" ng-model="widget.data['button_link']"> Show Button</label>
                        </p>
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-link-text">Link Text</label>
                    <div class="uk-form-controls">
                        <input id="wk-link-text" class="uk-form-width-medium" type="text" ng-model="widget.data['link_text']">
                    </div>
                </div>

                <div class="uk-form-row">
                    <label class="uk-form-label" for="wk-class">HTML Class</label>
                    <div class="uk-form-controls">
                        <input id="wk-class" class="uk-form-width-medium" type="text" ng-model="widget.data['class']">
                    </div>
                </div>

            </li>
        </ul>

    </div>
</div>
