@extends('layouts.app')

@section('content')

<br>
<br>



<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h3>İçerik Düzenleme</h3>
        </div>

        <div class="pull-right text-center">
            <form action="{{ route('contents.destroy', $content->id) }}" method="POST">

                @csrf
                @method('DELETE')

                <button type="submit" title="delete" style="border: none; background-color:transparent;">
                    <i class="btn btn-danger">DELETE</i>

                </button>
            </form>
            <b>
                Eğerki ilgili bahis sitesi kapandıysa, veya uygunsuz içerik ise silebiliriz.
            </b>
        </div>
    </div>
</div>

<br>
<div class=" border border-primary">
    <div class="col-lg-12 margin-tb pb-3">
        <br>
        <p>DURUM:</p>
        @if($content->status == 2 && $content->last_link && $content->last_title && $content->last_description && $content->last_content)
        <b class=" btn-success p-2">Yayınlandı.</b><br>
        @else
        <b class=" btn-warning p-2">Yayınlanmaya Hazır Değil!, Eksikler var!</b>
        <br>
        <br>

        <h5>Eksikler;</h5>
        @endif
        @if(!$content->last_link)
        - Yayınlanacak <b> Linkin </b> Yazılması Gerekiyor.<br>
        @endif
        @if(!$content->last_title)
        - Yayınlanacak <b> Başlığın </b> Yazılması Gerekiyor.<br>
        @endif

        @if(!$content->last_description)
        - Yayınlanacak <b> Açıklama </b> Yazılması Gerekiyor.<br>
        @endif
        @if(!$content->last_content)
        - Yayınlanacak <b> İçeriğin </b> Yazılması Gerekiyor.<br>
        @endif

    </div>
</div>



<form class="" action="{{ route('contents.update', $content->id) }}" method="POST">
    @csrf
    @method('PUT')





    <div id="accordion">
        <div class="card">
            <div class="card-header" id="genel">
                <h5 class="mb-0">
                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#genelalan" aria-expanded="true" aria-controls="genelalan">
                        Genel Bilgiler
                    </button>
                </h5>
            </div>

            <div id="genelalan" class="collapse show" aria-labelledby="genel" data-parent="#accordion">
                <div class="card-body">

                    <div class="">

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Başlık:</strong>
                                <textarea class="form-control" style="height:50px" name="last_title" maxlength="60" placeholder="Title">{{ $content->last_title }}</textarea>
                                <small>{!! $content->rewriter_title !!}</small>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Açıklama:</strong>
                                <textarea class="form-control" style="height:100px" name="last_description" maxlength="160" placeholder="Description">{{ $content->last_description }}</textarea>
                                <small>{!! $content->rewriter_description !!} </small>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Link:</strong>
                                <input class="form-control" type="text" id="last_link" name="last_link" placeholder="Link" value="{{ $content->last_link }}" />
                                <small>{!! $content->first_link !!} </small>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Kategori</strong>
                                <br>
                                <small>
                                    İlgili kategoriyle eşleştirelim.
                                </small>
                                <br>
                                <select name="category_id" id="category_id" style="width:200px;" class="operator">
                                    @foreach ($categories as $key => $category_item)
                                    @if (isset($category->id))
                                    @if ($category_item->id == $category->id)
                                    <option value="{{$category_item->id}}" selected>{{$category_item->name}}</option>
                                    @else
                                    <option value="{{$category_item->id}}">{{$category_item->name}}</option>
                                    @endif
                                    @else
                                    <option value="{{$category_item->id}}">{{$category_item->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <strong>Bahis Şirketi</strong>
                                <br>
                                <small>
                                    Burada bu yazının hangi bahis şirketine ait olduğunu seçmemiz gerekiyor, eğerki deneme bonusu veren siteler gibi genel bir yazı ise şirketsiz seçeneği seçilmeli.
                                </small>
                                <br>
                                <select name="bet_company_id" id="bet_company_id" style="width:200px;" class="operator">
                                    @foreach ($bet_companies as $key => $bet_company_item)
                                    @if (isset($bet_company->id))
                                    @if ($bet_company_item->id == $bet_company->id)
                                    <option value="{{$bet_company_item->id}}" selected>{{$bet_company_item->name}}</option>
                                    @else
                                    <option value="{{$bet_company_item->id}}">{{$bet_company_item->name}}</option>
                                    @endif
                                    @else
                                    <option value="{{$bet_company_item->id}}">{{$bet_company_item->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    </div>


                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="icerk">
                <h5 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#icerkalani" aria-expanded="false" aria-controls="icerkalani">
                        İçerik Alanı
                    </button>
                </h5>
            </div>
            <div id="icerkalani" class="collapse show" aria-labelledby="icerk" data-parent="#accordion">
                <div class="card-body">

                    <div class="row">
                        <div name="rewriter_area" id="rewriter_area" class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <br>



                                <div class="card">
                                    <div class="card-header" id="rewriterdiv">
                                        <h5 class="mb-0">
                                            <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#rewriterdivarea" aria-expanded="true" aria-controls="rewriterdivarea">
                                                Yeniden Yazdırma Alanı ( @if($content->status==0)
                                                <b style="color:red;"> YENİDEN YAZDIRILMAMIŞ!</b>
                                                @endif)
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="rewriterdivarea" class="collapse" aria-labelledby="rewriterdiv">
                                        <div class="card-body">


                                            <iframe id="iframearea" name="iframearea" width="100%" height="800" src="https://aiarticlespinner.co" title="İçerik Yeniden Yazdırma Alanı"></iframe>


                                        </div>
                                    </div>
                                </div>




                                <!--   <div class="border border-danger p-1">

                                    <b>Henüz içerik yeniden yazılmamış!</b>

                                    <p>Eğer İlk içeriği resimlerden html etiketlerinden ayırdıysan, Aşağıdaki butona tıklayarak içeriği yeniden yazdır.</p>


                                    <button onClick="rewriter_func()" name="rewriterButton" id="rewriterButton" type="button" style="border: none; background-color:transparent;">
                                        <i class="btn btn-success">YAZDIR</i>
                                    </button>

                                    <button name="rewriterButtonWarning" id="rewriterButtonWarning" type="button" style="display:none;border: none; background-color:transparent;">
                                        <i class="btn btn-warning">Yazdırılıyor...</i>
                                    </button>
                                    <br>

                                    <small><b>Bu işlemi bir kere yapabilirsin. İlk içeriği temizlediğinden emin ol</b></small>

                                </div>
 -->



                            </div>
                        </div>






                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>İlk İçerik </strong>
                                <p style="opacity: 0;">
                                    1. Eğer site içine gidicek bir link koymak istiyorsan <b>#-Görüntülenecek Yazı-#</b> şeklinde yazman gerekir.<br>
                                    2. Eğer site dışına gidicek bir link koymak istiyorsan <b>&-Görüntülenecek Yazı-&</b> şeklinde yazman gerekir.

                                    <br> Örneğin;<br>
                                    #-Canli Bahis-# <br>
                                    &-1xbet-giris-adresi-& <br>
                                </p>


                                <textarea class="ckeditor" id="first_content" name="first_content">{!! $content->first_content !!}</textarea>
                            </div>
                        </div>




                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Yayınlanacak İçerik</strong>

                                <p>
                                    1. Eğer site içine gidicek bir link koymak istiyorsan <b>#-Görüntülenecek Yazı-#</b> şeklinde yazman gerekir.<br>
                                    2. Eğer site dışına gidicek bir link koymak istiyorsan <b>&-Görüntülenecek Yazı-&</b> şeklinde yazman gerekir.

                                    <br> Örneğin;<br>
                                    #-Canli Bahis-# <br>
                                    &-1xbet-giris-adresi-& <br>
                                </p>



                                <textarea class="ckeditor" id="last_content" name="last_content">

                                {{ $content->last_content }}


                                </textarea>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>

    </div>


    <input type="hidden" id="content_id" name="content_id" value="{{$content->id}}">
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Kaydet</button>
    </div>

</form>



<script type="text/javascript">
    $(document).ready(function() {

        $('#last_link').change(function() {
            friststr = $('#last_link').val();
            str = $('#last_link').val();

            str = str.replace(/^\s+|\s+$/g, ''); // trim
            str = str.toLowerCase();

            // remove accents, swap ñ for n, etc
            var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
            var to = "aaaaaeeeeeiiiiooooouuuunc------";
            for (var i = 0, l = from.length; i < l; i++) {
                str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
            }

            str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes


            $('#last_link').val(str);

            if (friststr != str) {
                alert('linki dogru şekilde tekrardan yaz');
            }
        });


    });





    function rewriter_func() {




        var first_content = $('#first_content').val();

        if (first_content.length > 9999) {
            alert('ilk içerik 10000 karakterden büyük!');
            exit();
        }

        var content_id = $('#content_id').val();
        $("#rewriterButton").attr("style", "display:none");
        $("#rewriterButtonWarning").attr("style", "display:block");



        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                contentType: 'application/json; charset=utf-8'
            }
        });

        $.ajax({
            type: "POST",
            cache: false,
            url: "/rewriter",
            data: {
                first_content: first_content,
                content_id: content_id,
            },
            success: function(data) {
                console.log(data);

                if (data['status'] == 1) {
                    text = data['text'];
                    $("#rewriter_area").attr("style", "display:none");
                    $("#rewriterButton").attr("style", "display:block");
                    $("#rewriterButtonWarning").attr("style", "display:none");
                    CKEDITOR.instances['last_content'].setData(text);
                } else {
                    alert("Yeniden yazdırmada sorun var, lütfen geliştirici ile iletişime geçin.\n " + data['msg']['message']);
                    console.log(data['msg']);
                }

            }
        });
    }
</script>

@endsection