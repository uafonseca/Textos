app.answers = {
    index: {
        start: () => {
            if (IS_WRITABLE) {
                $('#evaluation').css('opacity', 0);
                $.confirm({
                    title: 'Aviso',
                    content: 'Acepte para comenzar',
                    buttons: {
                        confirm: {
                            text: 'Confirmar',
                            btnClass: 'btn-primary',
                            action: function () {
                                $('#evaluation').css('opacity', 1);
                                $.ajax({
                                    url: Routing.generate('start-answer', {id:ANSWER_ID}),
                                    type: 'post',
                                    success: function(response){
                                        if (response.type === 'success') {
                                            fetchdata();
                                         }
                                    }
                                   });
                            }
                        },
                        cancel: {
                            text: 'Cancelar',
                            btnClass: 'btn-warning',
                            action: function () {
                                history.back();
                                return false;
                            }
                        }
                    }
                });   
            }
            function fetchdata() {

            
                let minutes = MINUTES - 1;
                let seconds = 59;

                  // Run myfunc every second
                var myfunc = setInterval(function() {

                    if (seconds === 0) {
                        minutes -= 1;
                        seconds = 59;
                    } else {
                        seconds--;
                    }
                    if (minutes < 3) {
                        $('#timer').css('color','red')    
                     }
                    $('#timer').html(pad(minutes)+':'+pad(seconds))
                        
                    if (minutes <= 0 && seconds<= 0) {
                        clearInterval(myfunc);
                        $('.send').trigger('click');
                    }
                }, 1000);
                
                function pad(d) {
                    return (d < 10) ? '0' + d.toString() : d.toString();
                }
               }
        },
        submit: () => {
            $('.send').on('click', function (event) {
                event.preventDefault();
                /**
                 * store the id of answers
                 * @type {*[]}
                 */
                let choicesAnswers = [];
                /**
                 * store the choice values of the selected choices, default value is 0
                 * @type {*[]}
                 */
                let choicesValues = [];

                /**
                 * same procedure of the previous array
                 * @type {*[]}
                 */
                let choicesTextAnswers = [];
                let choicesTextValues = [];
                $('.choice-check').map(function () {
                    choicesAnswers.push($(this).attr('data-choice-answer-id'))
                    choicesValues.push($(this).is(':checked') ? $(this).val() : 0)
                })
                $('.choice-text').map(function () {
                    choicesTextAnswers.push($(this).attr('data-choice-answer-id'))
                    choicesTextValues.push($(this).val())
                })
                $.ajax({
                    url : Routing.generate('save-answer'),
                    type: 'POST',
                    data :{
                        choicesAnswers: choicesAnswers,
                        choicesValues : choicesValues,
                        choicesTextAnswers : choicesTextAnswers,
                        choicesTextValues : choicesTextValues,
                        ANSWER_ID :ANSWER_ID
                    },
                    success : (response) => {
                        if (response.type === 'success'){
                            toastr.success(response.message)
                            window.location.href = Routing.generate('user_dashboard')
                        }
                    }
                })
            })
        },
    }
}
$(() => {
    app.answers.index.start();
    app.answers.index.submit();
})