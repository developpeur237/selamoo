<div class="fixed-header-area hide-for-small">
    <div style="background-image: url('http://localhost/selamoo/wp-content/uploads/2020/08/banner.png');
    border-bottom:2px solid white" class=" Self-type pr typewrite header-text cw" data-period='4000' 
    data-type='["Bienvenue chez Selamoo! Votre site de vente en ligne sécurisé et adapter a tout types de commerçants.",
    "Vous étes débaleurs, vendeurs ambulant, Grossist, Troqueurs ?",
    " Vous avez quelque choses a vendre, a échanger ? Selamoo est a vos cotés",
    "Veillez nous contacter via gmail: Selamoo.shop@gmail.com, Facebook: page selamoo.shop, whatsapp:667785083",
    "Selamoo, aux coté des commerçants "]'>Hello! Nous somme la team Selamoo de Deltoro Corp <i class="auto-write"></i></div>
    <div class="fixed-header">
        
        <div class="row">
            
            <div class="large-9 columns">
                <!-- Logo -->
                <div class="logo-wrapper large-3 columns">
                    <?php echo digi_logo(); ?>
                </div>
                <div class="large-9 columns"><?php digi_search('full'); ?></div>
            </div>
            <div class="large-3 columns">
                <?php echo digi_header_icons(); ?>
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