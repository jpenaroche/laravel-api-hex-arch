export default {
    custom: {
        name: {
            required: 'Please enter your name'
        },
        email: {
            required: 'Please enter your email',
            email: 'Your email address is invalid'
        },
        consulta: {
            required: 'Please enter your consultation',
        },
        telefono: {
            required: 'Please enter your telephone number',
        },
        asunto: {
            required: 'Please enter the topic of your consulation',
        },
        otra_via: {
            required: 'Why other means contacted us?'
        },
        sugerencia: {
            required: 'Suggest something ... please write your question',
        },
        funcionalidad:{
            required:'Please select an option'
        },
        informacion_brindada:{
            required:'Please select an option'
        },
        nivel_confianza:{
            required:'Please select an option'
        },
        promociones_servicios:{
            required:'Please select an option'
        }
    },
    attributes: {
        name: 'name',
        email: 'email',
        consulta: 'consultation',
        sugerencia: 'sugerencia',
        telefono: 'telephone',
        asunto: 'topic',
    },
    required: 'required'
}