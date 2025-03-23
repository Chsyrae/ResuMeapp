import { defineStore } from "pinia";

export const templateStore = defineStore("template", {

    state: () => {
        return {
            templates: [],
            templatePagination: {
            search: ' ',
            current_page: 1,
            per_page: 0,
            total: 0,
            visible: 10
        },
            templateLoader: false,

            publicTemplates: [],
            publicTemplatePagination: {
            search: ' ',
            current_page: 1,
            per_page: 0,
            total: 0,
            visible: 10
        },
            publicTemplateLoader: false
        };
    },
    actions: {
        setTemplates(template) {
            this.templates                       = template.data;
            this.templatePagination.current_page = template.current_page;
            this.templatePagination.total        = template.total;
            this.templatePagination.per_page     = template.per_page;
        },
        changeLoaderStatus(){
            this.templateLoader = !this.templateLoader;
        },

        setPublicTemplates(publicTemplate) {
            this.publicTemplates                       = publicTemplate.data;
            this.publicTemplatePagination.current_page = publicTemplate.current_page;
            this.publicTemplatePagination.total        = publicTemplate.total;
            this.publicTemplatePagination.per_page     = publicTemplate.per_page;
        },
        changePublicLoaderStatus(){
            this.publicTemplateLoader = !this.publicTemplateLoader;
        },
    },
    getters: {},
    persist: true,
});