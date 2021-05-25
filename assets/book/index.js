app.books = {
  form: {
      index: () => {
          app.plugins.initSelect2('#book_category');
          app.plugins.initSelect2('#book_stage');
          app.plugins.initSelect2('#book_level');
          app.plugins.initSelect2('#book_source');

          let link = $('#book_link_active');
          let code = $('#book_htmlCode_active');

          let urlCard = $('#url-card');
          let htmlCard = $('#html-card');

          link.on('change',function(){
            const scope = $(this);
            if(scope.filter(":checked").val())
              code.prop('checked',false);
          })  
          
          code.on('change',function(){
            const scope = $(this);
            if(link.filter(":checked").val())
              link.prop('checked',false);
          })  

          $('input[type="checkbox"]').on('change',function(){
            if(link.filter(":checked").val()){
              if($('#book_link_uri').parent().parent().hasClass('disabled'))
                $('#book_link_uri').parent().parent().removeClass('disabled');
            }else{
              if(!$('#book_link_uri').parent().parent().hasClass('disabled'))
                $('#book_link_uri').parent().parent().addClass('disabled');
            }
            if(code.filter(":checked").val()){
              if($('#book_htmlCode_code').parent().parent().hasClass('disabled'))
                $('#book_htmlCode_code').parent().parent().removeClass('disabled');
            }else{
              if(!$('#book_htmlCode_code').parent().parent().hasClass('disabled'))
                $('#book_htmlCode_code').parent().parent().addClass('disabled');
            }
          })
      },
    initInput: () => {
        if( 'undefined' != typeof image_url_defaultPreview )
          app.plugins.initializeEmpresaFileInput('#book_portada_imagenFile_file', image_url_defaultPreview);
      }
  },
};

$(() => {
    app.books.form.index();
    app.books.form.initInput();
  });
