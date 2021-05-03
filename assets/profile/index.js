app.profile = {
    index: {
      loadAddress: () => {
        $('#user1_country').change(function() {
          // ... retrieve the corresponding form.
          var $form = $(this).closest('form');
          // Simulate form data, but only include the selected sport value.
          var data = {};
          data[$(this).attr('name')] = $(this).val();
          // Submit data via AJAX to the form's action path.
          $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            success: function(html) {
              $('#user1_city').replaceWith(
                  $(html).find('#user1_city')
              );
              app.plugins.initSelect2('#user_city');
              if($('#user1_city').val()){
                $('#user1_canton').prop('disabled',false);
              }
              else{
                $('#user1_canton').prop('disabled',true)
              }
              $('#user1_city').on('change',function (e){
                console.log($(this).val())
                if($(this).val()){
                  $('#user1_canton').prop('disabled',false);
                }
                else{
                  $('#user1_canton').prop('disabled',true)
                }
              });
            }
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
  