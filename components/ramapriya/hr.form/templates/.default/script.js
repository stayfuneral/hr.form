/**
 * Форма создания заявки на подбор персонала.
 * 
 * @var {int} user текущий пользователь
 * @var {object} queryData вопросы и ответы
 * @var {object} qualities описание качеств
 * @var {object} softSkills качества
 * @var {object} taskResult результат создания задачи
 * @var {object} required номера вопросов, которые являются обязательными
 */

window.onload = () => {

    const app = BX.Vue.create({
        el: '#app',
        data: {
            user: null,
            queryData: [],
            qualities: [],
            softSkills: [],
            taskResult: {},
            required: [1,2,3,4,5,7,8,9,13],
        },
        methods: {
            /**
             * 
             * @param {string} action название экшена в компоненте
             * @param {string} mode куда отправлять запрос (class - компонент, lib - модуль, и т.д.)
             * @param {object} params параметры запроса
             * 
             * @return {object}
             */
            async sendAjax(action, mode, params = {}) {

                const request = await BX.ajax.runComponentAction('ramapriya:hr.form', action, {
                    mode: mode,
                    data: params
                })

                return await request.data;

            },

            /**
             * Получает список вопросов и качеств и записывает данные в переменные queryData и qualities. Вызывается при запуске компонента
             * @uses queryData
             * @uses qualities
             */
            getQueryData() {
                const params = {
                    action: 'get_queries'
                };
                const request = this.sendAjax('sendRequest', 'class', params)

                request.then(response => {

                    if(response.current_user) {
                        this.user = response.current_user;
                    }                    

                    if(response.queries) {
                        for(let i = 0; i < response.queries.length; i++) {
                            let data = {
                                query: response.queries[i],
                                answer: ''
                            }
                            this.queryData.push(data);
                        }
                    }

                    if(response.qualities) {
                        this.qualities = response.qualities;
                    }

                    
                })
            },

            /**
             * Автоматически добавляет id к селекту выбора качеств
             * 
             * @param {string|int} id 
             * @return {string}
             */
            getSelectQualityId(id) {
                return 'select_quality_' + id;
            },

            /**
             * Записывает полученные данные из селекта
             * 
             * @uses softSkills
             * @param {string|int} priority 
             */
            changeQuality(priority) {
                const id = this.getSelectQualityId(priority);
                const select = document.getElementById(id);
                const key = new String(priority)
                const quality = select.options[select.selectedIndex].text;
                const skill = priority + ': ' + quality;
                this.softSkills.forEach(skills => {
                    if(skills.includes(skill)) {
                        skills = priority + ': ' + quality
                    }
                })
                this.softSkills.push(priority + ': ' + quality);
            },

            /**
             * Формирует название для создаваемой задачи
             * 
             * @uses queryData
             * @return {string}
             */
            setTaskTitle() {
                let title = 'Заявка на подбор персонала'
                this.queryData.forEach(item => {
                    if(item.query.includes('Должность (по факту/в вакансии)')) {
                        title += ', должность ' + item.answer
                        
                    }
                })
                return title;
            },

            /**
             * Формирует описание задачи из полученных вопросов
             * 
             * @uses queryData
             * @uses softSkills
             * 
             * @return {string}
             */
            setTaskDescription() {

                let description = ``;
                

                this.queryData.forEach(item => {


                    if(item.answer !== '') {
                        let answer = item.answer === '' ? '---' : item.answer;
                        description += '[b]'+ item.query + '[/b]';
                        description += `
` + answer + `
    
`;
                    }
                })
                if(this.softSkills.length > 0) {
                    description += `[b]Личностные качества [/b]
`;
                    this.softSkills.forEach(skill => {
                        description += skill + `
`;
                    })
                }
                return description

            },
            /**
             * Создаёт задачу в группе HR на основе заполненных данных
             */
            createTask() {

                let taskFields = {
                    TITLE: this.setTaskTitle(),
                    DESCRIPTION: this.setTaskDescription(),
                    CREATED_BY: this.user,
                    RESPONSIBLE_ID: 4632,
                    GROUP_ID: 42,
                    DESCRIPTION_IN_BBCODE: 'Y'
                }

                const createTask = this.sendAjax('createTask', 'class', {
                    action: 'create_task',
                    fields: taskFields
                });

                createTask.then(result => {
                    
                    switch(result.result) {
                        case 'success':
                            alert('Ваша заявка №'+result.task+' отправлена!');
                            break;
                        case 'error':
                            alert(result.error_description);
                            break;
                    }
                    
                })
            },
            /**
             * Проверяет, является ли вопрос обязательным
             * @param {int} int номер вопроса
             * @return {boolean}
             */
            isRequired(int) {
                return this.required.includes(int);
            },
        },
        created() {
            this.getQueryData();
        }
    });

}