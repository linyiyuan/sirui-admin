<template> 
  <div class="app-container">
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>点餐操作</span>
      </el-button>
    </el-card>
    <div class="table-container">
      <el-calendar :range="['2019-09-02', '2019-09-08']">
        <!-- 这里使用的是 2.5 slot 语法，对于新项目请使用 2.6 slot 语法-->
        <template slot="dateCell" slot-scope="{date, data}">
          <p :class="data.isSelected ? 'is-selected' : ''" v-if="data.day < nowDate" style="color: #c0c4cc; height:400px">
            {{ data.day.split('-').slice(1).join('-') }} {{ data.day == nowDate ? '✔️' : ''}}
            <br>
            <del>{{ list[data.day]}}</del>
          </p>
          <p :class="data.isSelected ? 'is-selected' : ''" v-if="data.day >= nowDate" @click="addMenu(data,day)">
            {{ data.day.split('-').slice(1).join('-') }} {{ data.day == nowDate ? '✔️' : ''}}
            <br>
            {{ list[data.day]}}
          </p>
        </template>
      </el-calendar>
    </div>
    <!-- 重置密码 -->
    <el-dialog title="点餐平台" :visible.sync="addMenuDialogVisible" width="1400px" :close-on-click-modal="false">
      <el-form ref="addMenuForm" :model="addMenuForm" label-width="120px">
        <el-form-item label="餐厅选择" style="margin-left: -40px">
          <el-select v-model="listQuery.restaurant_id" filterable placeholder="请选择" @change="searchMenuList()">
            <el-option v-for="item in restaurantSearchList" :key="item.value" :label="item.label" :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-card class="operate-container" shadow="never">
          <p>
            <i class="el-icon-tickets"></i>
            <span>菜谱分类</span>
            <div v-for="menus in menuList">
              <p style="font-size: 18px">{{ menus.label}} :</p>
              <el-checkbox-group v-model="orderMenuFormData.menu_id">
                <el-checkbox v-for="(menuValue,menuKey) in menus.menuList" :key="menuKey" :label="menuValue.menu_id" :value="menuValue.menu_id" @change="selectMenu(menuValue)">
                  {{ menuValue.menu_name}} ({{ menuValue.menu_amount }})
                </el-checkbox>
              </el-checkbox-group>
            </div>
          </p>
        </el-card>
        <el-card class="operate-container" shadow="never">
          <p>
            <i class="el-icon-tickets"></i>
            <span>订单信息</span>
            <el-form-item label="已选择的菜品：">
              <span v-for="item in menuSelected" style="font-size: 20px; margin-right: 20px">{{ item }} + </span>
            </el-form-item>
            <p style="float: right; margin-right: 120px">
              金额小计： <span style="color: red;font-size: 25px">￥ {{ orderMenuFormData.amount }}</span>
            </p>
          </p>
        </el-card>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="addMenuDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleSendOrderData()">下 单</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { menuList, createMenu, updateMenu, deleteMenu } from '@/api/menu/menu'
import { orderMenu, getMenuRecordByUid } from '@/api/menu/menuRecord'
import { restaurantList } from '@/api/menu/restaurant'
import { menuTypeList } from '@/api/menu/menuType'
import { formatDate } from '@/utils/date';
import store from '@/store'
const defaultListQuery = {
  restaurant_id: 1,
  type: 'groupByMenuType'
};
const defaultOrderMenuFormData = {
  'menu_id': [],
  'amount': 0,
}
export default {
  name: "userList",
  components: {},
  data() {
    return {
      listQuery: Object.assign({}, defaultListQuery),
      listLoading: true,
      date: new Date(),
      nowDate: null,
      list: [],
      menuList: [],
      restaurantSearchList: [],
      addMenuDialogVisible: false,
      menuSelected: {},
      orderMenuFormData: Object.assign({}, defaultOrderMenuFormData),
    }
  },
  created() {
    let nowDate = new Date();
    this.nowDate = formatDate(nowDate, 'yyyy-MM-dd')
    this.getMenuList();
    this.getRestaurantSearchList();
    this.getMenuRecordByUid();
  },
  filters: {
    formatLoginTime(time) {
      let date = new Date(time * 1000);
      return formatDate(date, 'yyyy-MM-dd hh:mm:ss')
    },
  },
  methods: {
    searchMenuList() {
      this.getMenuList();
    },
    handleSendOrderData() {

        // console.log(this.orderMenuFormData)
    },
    selectMenu(menuValue) {
      if (this.orderMenuFormData.menu_id.indexOf(menuValue.menu_id) != -1) {
        this.orderMenuFormData.amount += menuValue.menu_amount
        this.menuSelected[menuValue.menu_id] = menuValue.menu_name
      } else {
        this.orderMenuFormData.amount -= menuValue.menu_amount
        delete this.menuSelected[menuValue.menu_id]
      }
    },
    handleResetSearch() {
      this.listQuery = Object.assign({}, defaultListQuery);
    },
    handleSearchList() {
      this.listQuery.cur_page = 1;
      this.getList();
    },
    handleSizeChange(val) {
      this.listQuery.cur_page = 1;
      this.listQuery.page_size = val;
      this.getList();
    },
    handleCurrentChange(val) {
      this.listQuery.cur_page = val;
      this.getList();
    },
    getMenuList() {
      menuList(this.listQuery).then(response => {
        this.$nextTick(() => {
          this.menuList = response.data.list;
        })

      });
    },
    getMenuRecordByUid() {
      getMenuRecordByUid().then(response => {
        this.$nextTick(() => {
          this.list = response.data.list;
        })

      });
    },
    getRestaurantSearchList() {
      restaurantList({ type: 'search' }).then(response => {
        this.restaurantSearchList = response.data.list;
      });
    },
    addMenu(date) {
      this.addMenuDialogVisible = true;
    }
  }
}

</script>
<style scoped>
.input-width {
  width: 203px;
}

</style>
