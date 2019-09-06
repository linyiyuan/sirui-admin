<template> 
  <el-card class="form-container" shadow="never">
    <div slot="header" class="clearfix">
      <span>点餐配置</span>
    </div>
    <el-form :model="menuConfigData" ref="menuConfigForm" :rules="rules" label-width="150px">
      <el-form-item label="点餐开关：">
        <el-switch v-model="menuConfigData.order_switch" :active-value=1 :inactive-value=0 active-color="#13ce66" inactive-color="#ff4949" active-text="开启" inactive-text="关闭">
        </el-switch>
        <el-divider></el-divider>
      </el-form-item>
      <el-form-item label="点菜可选时间范围：">
        <el-input v-model="menuConfigData.time_range" class="input-width">
          <template slot="append">周</template>
        </el-input>
        <el-button type="primary">确定</el-button>
        <el-divider></el-divider>
      </el-form-item>
      <el-form-item label="点菜最大金额限制：">
        <el-input v-model="menuConfigData.order_max_amount" class="input-width">
          <template slot="append">元</template>
        </el-input>
        <el-button type="primary">确定</el-button>
        <el-divider></el-divider>
      </el-form-item>
    </el-form>
  </el-card>
</template>
<script>
import { menuConfigList, updateMenuConfig } from '@/api/menu/menuConfig';
const defaultMenuConfigData = {
  dinner_switch: null,
  breakfast_switch: null,
  id: null,
  lunch_switch: null,
  order_max_amount: null,
  order_switch: null,
  snack_switch: null,
  time_range: null,
};
export default {
  name: 'orderSetting',
  data() {
    return {
      menuConfigData: Object.assign({}, defaultMenuConfigData),
      menuConfigList: [],
    }
  },
  created() {
    this.getMenuConfigList();
  },
  methods: {
    confirm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.$confirm('是否要提交修改?', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            updateOrderSetting(1, this.orderSetting).then(response => {
              this.$message({
                type: 'success',
                message: '提交成功!',
                duration: 1000
              });
            })
          });
        } else {
          this.$message({
            message: '提交参数不合法',
            type: 'warning'
          });
          return false;
        }
      });
    },
    getMenuConfigList() {
      menuConfigList().then(response => {
        this.$nextTick(() => {
          this.menuConfigData = response.data.list
        })
      })
    }
  }
}

</script>
<style scoped>
.input-width {
  width: 50%;
}

.note-margin {
  margin-left: 15px;
}

</style>
