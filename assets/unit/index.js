app.unit = {
  form: {
    index: () => {
      app.plugins.initSelect2("#unit_book");
      $("#unit_pdf_pdfFile_file").fileinput({
        showPreview: false,
        showUpload: false,
        elErrorContainer: '#kartik-file-errors',
        allowedFileExtensions: ["pdf"]
    });
    },
  },
};

$(() => {
  app.unit.form.index();
});
