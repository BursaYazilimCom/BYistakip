<div class="background"></div>
<div class="card">
    <img class="logo" src="images/logo/by_logo.png">
    {{ Redirect::select('bilgi',true) }}
    <h2>Hoşgeldiniz</h2>

    @Form::csrf()->action('By/login')->open('submitForm',['id'=>'submitForm','class'=>'form'])
        @Form::vRequired()->id('username')->placeholder('Kullanıcı adınız')->text('username','')

        @Form::vRequired()->id('password')->placeholder('**********')->password('password','')
        <button>Giriş Yap</button>
    @Form::close()
    <footers>
    
        Yardıma İhtiyacım var! <a href="https://www.bursayazilim.com">Tıklayın</a>
    </footers>
</div>