<style>
    #sem_loading {
        position: fixed;
        right: 15px;
        bottom: 15px;
        width: 400px;
    }
    .ui.attached.info.load, .ui.info.load {
        -webkit-box-shadow: 0 0 0 1px #a9d5de inset, 0 0 0 0 transparent;
        box-shadow: 0 0 0 1px #a9d5de inset, 0 0 0 0 transparent;
    }
    .ui.info.load {
        background-color: #f8ffff;
        color: #276f86;
    }
    .ui.icon.load {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        width: 100%;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .ui.load:last-child {
        margin-bottom: 0;
    }
    .ui.load:first-child {
        margin-top: 0;
    }
    .ui.load {
        font-size: 1em;
    }
    .ui.load {
        position: relative;
        min-height: 1em;
        margin: 1em 0;
        background: #f8f8f9;
        padding: 1em 1.5em;
        line-height: 1.4285em;
        color: rgba(0,0,0,.87);
        -webkit-transition: opacity .1s ease,color .1s ease,background .1s ease,-webkit-box-shadow .1s ease;
        transition: opacity .1s ease,color .1s ease,background .1s ease,-webkit-box-shadow .1s ease;
        transition: opacity .1s ease,color .1s ease,background .1s ease,box-shadow .1s ease;
        transition: opacity .1s ease,color .1s ease,background .1s ease,box-shadow .1s ease,-webkit-box-shadow .1s ease;
        border-radius: .28571429rem;
        -webkit-box-shadow: 0 0 0 1px rgba(34,36,38,.22) inset, 0 0 0 0 transparent;
        box-shadow: 0 0 0 1px rgba(34,36,38,.22) inset, 0 0 0 0 transparent;
    }

    .ui.icon.load>.icon:not(.close) {
        display: block;
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: auto;
        line-height: 1;
        vertical-align: middle;
        font-size: 3em;
        opacity: .8;
    }
    .ui.load>:first-child {
        margin-top: 0;
    }
    .ui.load>.icon {
        margin-right: .6em;
    }
    i.icon.loading {
        height: 1em;
        line-height: 1;
        -webkit-animation: icon-loading 2s linear infinite;
        animation: icon-loading 2s linear infinite;
    }
    i.icon, i.icons {
        font-size: 1em;
    }
    i.icon {
        display: inline-block;
        opacity: 1;
        margin: 0 .25rem 0 0;
        width: 1.18em;
        height: 1em;
        font-family: Icons;
        font-style: normal;
        font-weight: 400;
        text-decoration: inherit;
        text-align: center;
        speak: none;
        font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        -webkit-font-smoothing: antialiased;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
    }
    .ui.icon.load .icon:not(.close)+.content {
        padding-left: 0;
    }
    .ui.icon.load>.content {
        display: block;
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        vertical-align: middle;
    }
    .ui.info.load .header {
        color: #0e566c;
    }
    .ui.load .header:not(.ui) {
        font-size: 1.14285714em;
    }
    .ui.load .header {
        display: block;
        font-family: Lato,'Helvetica Neue',Arial,Helvetica,sans-serif;
        font-weight: 700;
        margin: -.14285714em 0 0 0;
    }
</style>
<div style="position: relative;">
    <div class="ui info icon load" id="sem_loading" style=" display: none;">
        <i class="notched circle loading icon"></i>
        <div class="content">
            <div class="header">
                Cargando...
            </div>
            <p id="sem_loading_message"></p>
        </div>
    </div>
</div>