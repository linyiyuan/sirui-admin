<template>
    <div>
        <el-card class="box-card">
            <el-card shadow="never">
              <i class="el-icon-tickets"></i>
              <span>{{ $route.meta.title}}</span>
            </el-card>
            <el-tabs v-model="activeName">
                <el-tab-pane label="基本信息" name="index">

                    <!-- <div class="panel-body">
                        <ul>
                            <li v-for="v in config">
                                <el-row :gutter="20">
                                    <el-col :span="24">
                                        <i :class="v.icon"> &nbsp;{{ v.name}} : </i>
                                        {{ v.config }}
                                    </el-col>
                                </el-row>
                            </li>
                        </ul>
                    </div> -->
                    <el-table
                      border
                      :data="config"
                      style="width: 100%">
                      <el-table-column
                        label="配置项"
                        width="200">
                        <template slot-scope="scope">
                          <span style="margin-left: 10px">{{ scope.row.name }}</span>
                        </template>
                      </el-table-column>
                      <el-table-column
                        label="配置信息"
                        >
                        <template slot-scope="scope">
                          <span style="margin-left: 10px">{{ scope.row.config }}</span>
                        </template>
                      </el-table-column>
                    </el-table>
                </el-tab-pane>
                <el-tab-pane label="前端信息" name="api">
                   <el-table
                      border
                      :data="api_config"
                      style="width: 100%">
                      <el-table-column
                        label="配置项"
                        width="200">
                        <template slot-scope="scope">
                          <span style="margin-left: 10px">{{ scope.row.name }}</span>
                        </template>
                      </el-table-column>
                      <el-table-column
                        label="配置信息"
                        >
                        <template slot-scope="scope">
                          <span style="margin-left: 10px">{{ scope.row.config }}</span>
                        </template>
                      </el-table-column>
                    </el-table>
                </el-tab-pane>
            </el-tabs>
        </el-card>
    </div>
</template>

<script>
    import {getSystemConfig} from '@/api/system'
    export default {
        data() {
            return {
                activeName: 'index',
                config: [],
                api_config: []
            }
        },

        mounted() {
            getSystemConfig().then(response => {
                this.config = response.data;
            });
       
            this.api_config = [
                {
                    name: '项目名',
                    config: '万炮捕鱼大富豪',
                    icon: 'el-icon-delete-solid'
                },
                {
                    name: '前端框架',
                    config: 'Vue 2.0',
                    icon: 'fa fa-html5'
                },
                {
                    name: '前端UI框架',
                    config: 'Element UI 2.4.11',
                    icon: 'fa fa-css3'
                },
            ];

        },

        components: {}
    }
</script>
<style>
  
</style>
