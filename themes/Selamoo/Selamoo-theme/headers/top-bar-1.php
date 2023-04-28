<link rel="stylesheet" type="text/css" href="pe-icon-7-stroke.css">
<style>
    .Self-type{
background: #3f48cc;
color: white;
text-align: center;
border-top: 25px;
padding-top: 5px;
padding-left: 5%;
padding-bottom: 5px;
font-weight: 600;
background-size: contain;
background-repeat: round;
    }
    .auto-write {
    content: "\e69e";
    }
    .auto-write::before {
    content: "\e69e";
    }

</style>
<?php 
$topbar_left = (!isset($nasa_opt['topbar_left_show']) || $nasa_opt['topbar_left_show'] == 1) ? ((isset($nasa_opt['topbar_left']) && $nasa_opt['topbar_left'] != '') ? do_shortcode($nasa_opt['topbar_left']) : '<i style="font-size: 130%; vertical-align: text-top; margin-right: 8px;" class="icon pe-7s-smile primary-color"></i>' . esc_html__('Selam ooooo...', 'digi-theme')) : '';
?>

<div style="background-image: url('http://localhost/selamoo/wp-content/uploads/2020/08/banner.png');" 
class=" Self-type pr typewrite header-text cw" data-period='4000' data-type='["Bienvenue chez Selamoo! Votre site de vente en ligne sécurisé et adapter a tout types de commerçants.","Vous étes débaleurs, vendeurs ambulant, Grossist, Troqueurs ?"," Vous avez quelque choses a vendre, a échanger ? Selamoo est a vos cotés",
    "Veillez nous contacter via gmail: Selamoo.shop@gmail.com, Facebook: page selamoo.shop, <a style=\"color:chartreuse\" href=\"https://api.whatsapp.com/send?phone=237655535687\">whatsapp:667785083</a>","Selamoo, aux coté des commerçants."]'>Hello! Nous somme la team Selamoo de DeltoroCorp. <i class="auto-write"></i></div>
<div id="top-bar" class="top-bar top-bar-type-1">
    <div class="row">
        <div class="large-12 columns">
            <div class="left-text left">
                <div class="inner-block">
                    <?php echo $topbar_left; ?>
                </div>
            </div>
            <div class="right-text right">
                <div class="topbar-menu-container">
                    <?php digi_get_menu('topbar-menu', 'nasa-topbar-menu', 1); ?>
                    <?php echo digi_language_flages(); ?>
                    <?php echo digi_header_icons(true, true, false); ?>
                    <?php echo digi_tiny_account(true); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var TxtType = function(el, toRotate, period) {
        this.toRotate = toRotate;
        this.el = el;
        this.loopNum = 0;
        this.period = parseInt(period, 10) || 4000;
        this.txt = '';
        this.tick();
        this.isDeleting = false;
    };

    TxtType.prototype.tick = function() {
        var i = this.loopNum % this.toRotate.length;
        var fullTxt = this.toRotate[i];

        if (this.isDeleting) {
        this.txt = fullTxt.substring(0, this.txt.length - 1);
        } else {
        this.txt = fullTxt.substring(0, this.txt.length + 1);
        }

        this.el.innerHTML = '<span class="wrap">'+this.txt+'</span><span class="typed-cursor">|</span>';

        var that = this;
        var delta = 100 - Math.random() * 100;

        if (this.isDeleting) { delta /= 2; }

        if (!this.isDeleting && this.txt === fullTxt) {
        delta = this.period;
        this.isDeleting = true;
        } else if (this.isDeleting && this.txt === '') {
        this.isDeleting = false;
        this.loopNum++;
        delta = 500;
        }

        setTimeout(function() {
        that.tick();
        }, delta);
    };

    window.onload = function() {
        var elements = document.getElementsByClassName('typewrite');
        for (var i=0; i<elements.length; i++) {
            var toRotate = elements[i].getAttribute('data-type');
            var period = elements[i].getAttribute('data-period');
            console.log(elements)
            if (toRotate) {
              new TxtType(elements[i], JSON.parse(toRotate), period);
            }
         }
    };
</script>