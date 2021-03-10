app.profile = {
    index: {
      loadAddress: () => {
        var currentCities = [];
    
        var BATTUTA_KEY = "d1852eb18710a5342c07a673014fc9c9";
        
        url =
          "https://geo-battuta.net/api/country/all/?key=" +
          BATTUTA_KEY +
          "&callback=?";
        app.plugins.initSelect2("#user1_country");
        app.plugins.initSelect2("#user1_provincia");
        app.plugins.initSelect2("#user1_canton");
  
        $("<option></option>")
        .append('--SELECCIONE--')
        .appendTo($("#user1_country"));
  
        $("<option></option>")
        .append('--SELECCIONE--')
        .appendTo($("#user1_provincia"));
  
        $("<option></option>")
        .append('--SELECCIONE--')
        .appendTo($("#user1_canton"));
  
        $.getJSON(url, function (countries) {
          $.each(countries, function (key, country) {
            let select = $("#user1_country");
            let choice = select.val() === country.code;
            var newOption = new Option(country.name, country.code, choice, choice);
            $(newOption).appendTo(select);
          });
  
          $("#user1_country").trigger("change");
        });
  
        $("#user1_country").on("change", function () {
          countryCode = $("#user1_country").val();
        
          url =
            "https://geo-battuta.net/api/region/" +
            countryCode +
            "/all/?key=" +
            BATTUTA_KEY +
            "&callback=?";
  
          $.getJSON(url, function (regions) {
            $("#user1_provincia option").remove();
  
            $.each(regions, function (key, region) {

                let select = $("#user1_provincia");
                let choice = select.val() === region.region;
                var newOption = new Option(region.region, region.region, choice, choice);
                $(newOption).appendTo(select);
            });
  
            $("#user1_provincia").trigger("change");

          });
        });

        $("#user1_provincia").on("change", function () {
          countryCode = $("#user1_country").val();
          region = $("#user1_provincia").val();
          url =
            "http://geo-battuta.net/api/city/" +
            countryCode +
            "/search/?region=" +
            region +
            "&key=" +
            BATTUTA_KEY +
            "&callback=?";
  
          $.getJSON(url, function (cities) {
            currentCities = cities;
            var i = 0;
            $("#user1_canton option").remove();
  
            $.each(cities, function (key, city) {

                let select = $("#user1_canton");
                let choice = select.val() == i;

                if(choice){
                    $('#liveIn').html(city.city)
                }
               
                var newOption = new Option(city.city, i++, choice, choice);
                $(newOption).appendTo(select);

    
            });
            $("#user1_canton").trigger("change");
          });
        });
      },
      upload: () =>{
        let STYLE_SETTING = 'style="width:{width};height:{height};"',
        img_field =
          "<img class='img-fluid rounded-circle mb-2' src='value_replace'>";

      $("#user1_avatar_imagenFile_file").fileinput({
        'defaultPreviewContent': [
          img_field.replace("value_replace", image_url_defaultPreview),
        ],
        previewSettings: {
          image: { width: "190px", height: "200px" },
        },
        initialCaption: "&nbsp; Foto", // Muestro que esa foto es la original del usuario
        overwriteInitial: false, // Cuando limpien el preview se vuelve a mostrar el initialCaption
        showCaption: true,
        showUpload: false,
        browseLabel: "&nbsp;Buscar",
        allowedFileExtensions: ["image"],
        removeLabel: "&nbsp;Quitar",
        allowedFileExtensions: ["jpg", "png", "gif", "svg", "jpeg"],

        layoutTemplates: {
          main1:
            "" +
            "{preview}" +
            '<div class="kv-upload-progress hide"></div>\n' +
            "       {browse}\n" +
            "       {remove}\n" +
            "       {cancel}\n" +
            "       {upload}\n",
          btnDefault:
            '<button type="{type}" tabindex="500" title="{title}" class="file-input-btn {css}"{status}>{icon}{label}</button>',
          icon: '<i class="fa fa-camera-retro"></i>&nbsp;',
        },
        previewTemplates: {
          image:
            '   <img src="{data}" class="card-img-top img-fluid rounded-circle mb-2" title="{caption}" alt="{caption}" ' +
            STYLE_SETTING +
            ">\n",
        },
      });
      }
    },
  };
  
  $(() => {
    app.profile.index.loadAddress();
    app.profile.index.upload();
  });
  