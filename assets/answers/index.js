app.answers = {
    index: {
        validate: () => {

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
                        choicesTextValues : choicesTextValues
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
    app.answers.index.validate();
    app.answers.index.submit();
})