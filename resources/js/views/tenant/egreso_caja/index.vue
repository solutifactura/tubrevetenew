<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Egresos de Caja</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Monto</th>
                        <th>Observación</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in records">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.created_at }}</td>
                        <td>{{ row.usuario }}</td>
                        <td>{{ row.monto }}</td>                    
                        <td>{{ row.observacion }}</td>
                        <td class="text-right">
                           
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                </div>
            </div>
        </div>
        <tenant-egreso-caja-form :showDialog.sync="showDialog"                   
                    :recordId="recordId"></tenant-egreso-caja-form>
    </div>
</template>

<script>

    import EgresoCajaForm from './form.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
       
        mixins: [deletable],
        components: {EgresoCajaForm},
        data() {
            return {
                showDialog: false,
                resource: 'egreso-caja',
                recordId: null,
                records: [],
            }
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
            this.getData()
        },
        methods: {
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data
                    })
            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
