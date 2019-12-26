<template>
    <div class="box ">
        <div class="box-body no-padding">
            <div class="el-form-item  col-xs-12">
                <div class="el-form-item__content">
                    <el-date-picker :inline=true v-model="d" type="date" name="d" placeholder="Inicio"></el-date-picker>
                </div>
            </div>
            <div class="el-form-item  col-xs-12">
                <div class="el-form-item__content">
                    <el-date-picker v-model="a" :inline=true type="date" name="a" placeholder="Término"></el-date-picker>
                </div>
            </div>
            <div class="el-form-item  col-xs-12">
                <div class="el-form-item__content">
                    <el-select v-model="client2" name="client" clearable placeholder="Establecimiento">
                        <el-option v-for="client in clients" :key="client.id" :label="client.name.toUpperCase()" :value="client.id"></el-option>
                    </el-select>
                </div>
            </div>
            
            <div class="el-form-item  col-xs-12">
                <div class="el-form-item__content">
                    <button class="btn btn-custom" type="submit"><i class="fa fa-search"></i> Buscar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
           'clients': {
                required: true
            }, 
            
            'data_d': {
                required: false,
                default: ''
            },
            'data_a': {
                required: false,
                default: ''
            },

            'client': {
                required: false,
                default: ''
            }
        
        },
        data() {
            return {
               
                d: '',
                a: '',
                client2:null
              
            }
        },
        created() {
         
            this.d = (this.data_d != '') ? moment(this.data_d) : '';
            this.a = (this.data_a != '') ? moment(this.data_a) : '';
            this.client2 = (this.client != '') ? this.client : null;
        }
    }
</script>
