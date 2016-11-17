<template>
    <div id="incidents" class="col-sm-12">
        <v-server-table url="/admin/incidents/get" :columns="columns" :options="options"></v-server-table>
    </div>
</template>

<script>
    import React from 'react';

    export default {
        methods: {
      
        },
        data() {
            return {
                columns: ['approved', 'date', 'title', 'city', 'state', 'moderate'],
                options: {
                    orderBy: { 
                        column: 'approved',
                        ascending: true
                    }, 
                    sortable: ['approved', 'date', 'city', 'state'],
                    templates: {
                        approved(h, row) {
                            if(row.approved == 1) {
                                var fa_class = 'fa fa-check inline-success';
                            } else if(row.approved == 0) {
                                var fa_class = 'fa fa-ban inline-danger';
                            } else {
                                var fa_class = 'fa fa-question';
                            }

                            return h('i', {
                                    attrs:{
                                        'class': fa_class
                                    }
                                });                        
                        },
                        moderate(h, row) {
                            return h('a', { attrs: { role: 'button', class: 'btn btn-info btn-xs btn-noborder', href: '/admin/incidents/' + row.id} }, [
                                        h('i', { attrs:{ 'class': 'fa fa-check' }}),
                                        'Moderate'
                            ])
                        }
                    },

                }, 

            }
        },    
    }

</script>