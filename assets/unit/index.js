// import 'jquery-confirm'
app.unit = {
  form: {
    index: () => {
      app.plugins.initSelect2("#unitType_book");


      $('.add-entry').on('click', function(event){
        event.preventDefault();
        const scope = $(this);
        app.dialogs.create({
          url:Routing.generate('create-activity',{uuid:unit_uuid, type:scope.data('type')}),
          containerFluid:true,
          columnClass:'col-md-8 col-md-offset-2'
        });
      })


      let STYLE_SETTING = 'style="width:{width};height:{height};"',
        img_field =
          "<embed src='value_replace' style='height: 100%'  type='application/pdf'>";

      $("#unitType_pdf_pdfFile_file").fileinput({
        'defaultPreviewContent': [
          img_field.replace("value_replace", image_url_defaultPreview),
        ],
        previewSettings: {
          // image: { width: "190px", height: "200px" },
        },
        initialCaption: "&nbsp; Foto", // Muestro que esa foto es la original del usuario
        overwriteInitial: false, // Cuando limpien el preview se vuelve a mostrar el initialCaption
        showCaption: true,
        showUpload: false,
        browseLabel: "&nbsp;Buscar",
        allowedFileExtensions: ["pdf"],
        removeLabel: "&nbsp;Quitar",
        allowedFileExtensions: ["pdf"],

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
            '   <img src="{data}" class="card-img-top" title="{caption}" alt="{caption}" ' +
            STYLE_SETTING +
            ">\n",
        },
      });
    },

    updateActivities :() => {
        $.get(Routing.generate('all-activites',{uuid:unit_uuid}), function(response){
            $('#all-activites').html(response);
        });
    },

    collection: () => {
      app.plugins.initPrototype(".activity-collection", {});
    },
  },
};

$(() => {
  app.unit.form.index();
  app.unit.form.updateActivities();
});
