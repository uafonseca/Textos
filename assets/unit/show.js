import "pdfjs";

import "jspdf";

import "fabric";

import "buffer";

var pdfjsLib = require("pdfjs-dist");

pdfjsLib.GlobalWorkerOptions.workerSrc = "../build/worker/pdf.worker.js";

import { PDFAnnotate } from "./pdfannotate";

app.unitPDF = {
  show: {
    index: () => {
      var pdf = new PDFAnnotate("container-pdf", pdfUrl, {
        onPageUpdated(page, oldData, newData) {
          console.log(page, oldData, newData);
        },
        ready() {
          console.log("Plugin initialized successfully");
        },
        scale: 1.5,
        pageImageCompression: "MEDIUM", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
      });

      function changeActiveTool(event) {
        var element = $(event.target).hasClass("tool-button")
          ? $(event.target)
          : $(event.target).parents(".tool-button").first();
        $(".tool-button.active").removeClass("active");
        $(element).addClass("active");
      }

      $('#enableSelector').on('clink',function(event) {
        event.preventDefault();
        changeActiveTool(event);
        pdf.enableSelector();
      }) 

      $('#enablePencil').on('click',function(event) {
        event.preventDefault();
        changeActiveTool(event);
        pdf.enablePencil();
      }) 

      $('#enableAddText').on('click',function(event) {
        event.preventDefault();
        changeActiveTool(event);
        pdf.enableAddText();
      })

      $('#enableAddArrow').on('click',function(event) {
        event.preventDefault();
        changeActiveTool(event);
        pdf.enableAddArrow();
      }) 
      $('#addImage').on('click',function(event) {
        event.preventDefault();
        pdf.addImageToCanvas();
      })

      $('#enableRectangle').on('click',function(event) {
        event.preventDefault();
        changeActiveTool(event);
        pdf.setColor("rgba(255, 0, 0, 0.3)");
        pdf.setBorderColor("blue");
        pdf.enableRectangle();
      })

      $('#deleteSelectedObject').on('click',function(event) {
        event.preventDefault();
        pdf.deleteSelectedObject();
      }) 

      $('#savePDF').on('click',function(event) {
         // pdf.savePdf();
         pdf.savePdf("sample.pdf"); // save with given file name
      }) 

      $('#clearPage').on('click',function(event) {
        pdf.clearActivePage();
      }) 

      $('#showPdfData').on('click',function(event) {
        var string = pdf.serializePdf();
        $("#dataModal .modal-body pre").first().text(string);
        // PR.prettyPrint();
        $("#dataModal").modal("show");
      })

      $(function () {
        $(".color-tool").click(function () {
          $(".color-tool.active").removeClass("active");
          $(this).addClass("active");
          color = $(this).get(0).style.backgroundColor;
          pdf.setColor(color);
        });

        $("#brush-size").change(function () {
          var width = $(this).val();
          pdf.setBrushSize(width);
        });

        $("#font-size").change(function () {
          var font_size = $(this).val();
          pdf.setFontSize(font_size);
        });
      });
    },
  },
};

$(() => {
  app.unitPDF.show.index();
});
