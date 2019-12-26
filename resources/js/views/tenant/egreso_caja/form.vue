<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Usuario</label>
                            <el-input v-model="usuarios.name" :disabled="true"></el-input>
                           
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.monto}">
                            <label class="control-label">Monto</label>
                            <el-input v-model="form.monto" placeholder="0.00"></el-input>
                            <small class="form-control-feedback" v-if="errors.monto" v-text="errors.monto[0]"></small>
                        </div>
                    </div>
                    
                  
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.observacion}">
                            <label class="control-label">Observaciones</label>
                          
                            <el-input
                                type="textarea"
                                autosize
                                v-model="form.observacion">
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.observacion" v-text="errors.observacion[0]"></small>
                        </div>
                    </div>
                   
                    
                   
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>

</template>

<script>

    import {EventBus} from '../../../helpers/bus'

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'egreso-caja',
                errors: {},
                form: {},              
                usuarios: {},
                usuario_idx: null,
                establishment_idx: null,
            }
        },
        async created() {
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {                    
                    this.usuarios = response.data.user
                   
                    this.usuario_idx = response.data.user.id 
                    this.establishment_idx = response.data.user.establishment_id 
                    
                })
            await this.initForm()
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null, 
                    user_id: this.usuario_idx,
                    establishment_id: this.establishment_idx,                 
                    monto: null,
                    observacion: null,
                    date_of_issue: moment().format('YYYY-MM-DD'),                                                 
                }

           
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar Egreso de Caja':'Nuevo Egreso de Caja'
              
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                        })
                }
            },
            submit() {
                console.log(this.form)
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)               
                    .then(response => {
                        if (response.data.success) { 
                                                                           
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>

