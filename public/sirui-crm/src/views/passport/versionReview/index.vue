<template> 
  <div class="app-container">
    <el-card class="filter-container" shadow="never">
      <div>
        <i class="el-icon-search"></i>
        <span>筛选搜索</span>
        <el-button
          style="float:right"
          type="primary"
          @click="handleSearchList()"
          size="small">
          查询搜索
        </el-button>
        <el-button
          style="float:right;margin-right: 15px"
          @click="handleResetSearch()"
          size="small">
          重置
        </el-button>
      </div>
      <div style="margin-top: 15px">
        <el-form :inline="true" :model="listQuery" size="small">
          <el-form-item label="状态搜索：">
            <el-select v-model="listQuery.type" placeholder="请选择显示条件">
              <el-option value=1 label="显示最高版本"></el-option> 
              <el-option value=0 label="显示全部"></el-option> 
            </el-select>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
      <el-button style="float: right;" icon="el-icon-plus" type="primary" size="mini" @click="handleAddVersionReview">添加审核
      </el-button>
      <el-button style="float: right;margin-right: 20px" icon="el-icon-plus" type="primary" size="mini" @click="handleViewNote">查看备注
      </el-button>
    </el-card>
    <div class="table-container">
      <el-table ref="versionReviewTable" :data="list" style="width: 100%;" v-loading="listLoading" border>
        <el-table-column sortable label="包名" prop="versionReviewname" width="400" align="center">
          <template slot-scope="scope">{{scope.row.bundle_id}}</template>
        </el-table-column>
        <el-table-column sortable label="包名描述" prop="versionReviewname" width="200" align="center">
          <template slot-scope="scope">{{scope.row.bundle_desc}}</template>
        </el-table-column>
        <el-table-column label="包版本" width="100" align="center">
          <template slot-scope="scope">{{scope.row.bundle_version}}</template>
        </el-table-column>
        <el-table-column sortable label="审核状态" prop="version_review_status" width="250" align="center">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.version_review_status" :active-value=1 :inactive-value=0 active-color="#13ce66" inactive-color="#ff4949" active-text="允许热更" inactive-text="不允许热更" @change="changeVersionReviewStatus(scope.row)">
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column sortable label="默认指定渠道" prop="default_pid" width="150" align="center">
          <template slot-scope="scope">{{scope.row.default_pid}}</template>
        </el-table-column>
        <el-table-column sortable label="创建时间" width="230" prop="created_at" align="center">
          <template slot-scope="scope">{{ scope.row.created_at}}</template>
        </el-table-column>
        <el-table-column sortable label="更新时间" width="230" prop="updated_at" align="center">
          <template slot-scope="scope">{{ scope.row.updated_at}}</template>
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button icon="el-icon-view" type="warning" size="mini" @click="handleViewBundleIdPid(scope.row.bundle_id)">查看渠道({{scope.row.pidCount}})</el-button>
            <el-button icon="el-icon-edit" type="primary" size="mini" @click="handleEditVersionReview(scope.$index, scope.row)">编辑</el-button>
            <el-button icon="el-icon-delete" type="danger" size="mini" @click="handleDeleteVersionReview(scope.$index, scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" layout="total, sizes,prev, pager, next,jumper" :current-page.sync="listQuery.cur_page" :page-size="listQuery.page_size" :page-sizes="[5,10,15]" :total="total">
      </el-pagination>
    </div>

    <!-- 添加/修改审核 -->
    <el-dialog title="添加/修改审核" :visible.sync="versionReviewDialogVisible" width="700px" :close-on-click-modal="false">
      <el-form ref="versionReviewForm" :model="versionReviewFormData" label-width="120px" :rules="versionReviewRules">
        <el-form-item label="包名" prop="bundle_id">
          <el-input type="text" v-model="versionReviewFormData.bundle_id" placeholder="请输入包名" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="包名" prop="bundle_id">
          <el-input type="text" v-model="versionReviewFormData.bundle_desc" placeholder="请输入包名描述" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="包版本" prop="bundle_version">
          <el-input type="text" v-model="versionReviewFormData.bundle_version" placeholder="请输入包版本" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="默认指定渠道" prop="default_pid">
          <el-input type="text" v-model="versionReviewFormData.default_pid" placeholder="请输入默认指定渠道" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="审核状态">
          <el-radio-group v-model="versionReviewFormData.version_review_status">
            <el-radio :label="1">启动</el-radio>
            <el-radio :label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="上线状态">
          <el-radio-group v-model="versionReviewFormData.bundle_status">
            <el-radio :label="1">测试</el-radio>
            <el-radio :label="2">上线</el-radio>
            <el-radio :label="3">上线（禁止修改）</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="versionReviewDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleSendData('versionReviewForm')">确 定</el-button>
      </div>
    </el-dialog>

    <!-- 包对应渠道列表 -->
    <el-dialog title="包对应渠道列表" :visible.sync="bundleIdPidDialogVisible" width="700px" :close-on-click-modal="false">
      <el-card class="operate-container" shadow="never">
        <i class="el-icon-tickets"></i>
        <span>数据列表</span>
        <el-button style="float: right;" icon="el-icon-plus" type="primary" size="mini" @click="handleAddBundleIdPid">添加渠道
        </el-button>
      </el-card>
      <div class="table-container">
        <el-table ref="bundleIdPidTable" :data="bundleIdPidList" style="width: 100%;" v-loading="listLoading" border>
          <el-table-column sortable label="包名" prop="bundle_id"  align="center">
            <template slot-scope="scope">{{scope.row.bundle_id}}</template>
          </el-table-column>
          <el-table-column sortable label="渠道ID" prop="pid" width="100" align="center">
            <template slot-scope="scope">{{scope.row.pid}}</template>
          </el-table-column>
          <el-table-column sortable label="排序" prop="sort" width="80" align="center">
            <template slot-scope="scope">{{scope.row.sort}}</template>
          </el-table-column>
          <el-table-column label="操作" align="center">
            <template slot-scope="scope">
              <el-button icon="el-icon-edit" type="primary" size="mini" @click="handleEditBundleIdPid(scope.$index, scope.row)">编辑</el-button>
              <el-button icon="el-icon-delete" type="danger" size="mini" @click="handleDeleteBundleIdPid(scope.row.id)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <div slot="footer">
        <el-button size="small" @click="bundleIdPidDialogVisible = false">关 闭</el-button>
      </div>
    </el-dialog>

    <!-- 添加/修改包对应渠道 -->
    <el-dialog title="添加/修改渠道" :visible.sync="changeBundleIdPidDialogVisible" width="700px" :close-on-click-modal="false">
      <el-form ref="bundleIdPidForm" :model="bundleIdPidFormData" label-width="120px" :rules="bundleIdPidRules">
        <el-form-item label="包名" prop="bundle_id">
          <el-input type="text" v-model="bundleIdPidFormData.bundle_id" placeholder="请输入包名" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="渠道" prop="pid">
          <el-input type="text" v-model="bundleIdPidFormData.pid" placeholder="请输入包名描述" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="排序" prop="sort">
          <el-input type="text" v-model="bundleIdPidFormData.sort" placeholder="请填写排序" auto-complete="off" size="medium"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="changeBundleIdPidDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleSendBundleIdPidData('bundleIdPidForm')">确 定</el-button>
      </div>
    </el-dialog>

    <el-dialog title="备注" :visible.sync="noteDialogVisible" width="600px" :close-on-click-modal="false">
      <el-form ref="versionReviewNote" :model="versionReviewNoteData" label-width="90px">
        <el-form-item label="备注">
          <el-input type="textarea" v-model="versionReviewNoteData.note" placeholder="请填写备注" auto-complete="off" size="medium" :rows=12></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="noteDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleSendVersionReviewNote('versionReviewNoteData')">保 存</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { versionReviewList, createVersionReview, updateVersionReview, deleteVersionReview, changeVersionReview } from '@/api/passport/versionReview'
import { bundleIdPidList, createBundleIdPid, updateBundleIdPid, deleteBundleIdPid } from '@/api/passport/bundleIdPid'
import { formatDate } from '@/utils/date';
import store from '@/store'
const defaultListQuery = {
  cur_page: 1,
  page_size: 15,
  type: 1,
};
const defaultVersionReviewFormData = {
  id: null,
  bundle_id: null,
  bundle_desc: null,
  bundle_version: null,
  default_pid: 0,
  version_review_status: 0,
  bundle_status: 1,
};
const defaultBundleIdPidFormData = {
  id: null,
  bundle_id: null,
  pid: null,
  sort: 0,
};
const defaultVersionReviewNoteData = {
  'note' : null
}
export default {
  name: "versionReviewList",
  components: {},
  data() {
    return {
      listQuery: Object.assign({}, defaultListQuery),
      listLoading: true,
      list: [],
      total: 0,
      bundleIdPidList: [],
      bundleIdPidTotal: 0,
      tempBundId: null,
      versionReviewDialogVisible: false,
      bundleIdPidDialogVisible: false,
      changeBundleIdPidDialogVisible: false,
      noteDialogVisible: false,
      versionReviewFormData: Object.assign({}, defaultVersionReviewFormData),
      bundleIdPidFormData: Object.assign({}, defaultBundleIdPidFormData),
      versionReviewNoteData:Object.assign({}, defaultVersionReviewNoteData),
      isEdit: false,
      bundleIdPidIsEdit: false,
      versionReviewRules: {
        versionReview_name: [
          { required: true, message: '请输入审核名', trigger: 'blur' }
        ],
      },
      bundleIdPidRules: {
        bundle_id: [
          { required: true, message: '请输入包名', trigger: 'blur' }
        ],
        pid: [
          { required: true, message: '请输入渠道ID', trigger: 'blur' }
        ],
      },
    }
  },
  created() {
    this.getList();
  },
  filters: {
    formatLoginTime(time) {
      let date = new Date(time * 1000);
      return formatDate(date, 'yyyy-MM-dd hh:mm:ss')
    },
    versionReview_status(versionReview_status) {
      if (versionReview_status == 1) {
        return '开启'
      } else {
        return '关闭'
      }
    }
  },
  methods: {
    handleViewNote() {
      this.noteDialogVisible = true;
    },
    handleSearchMaxVersion() {
      this.listQuery.type = this.type
    },
    handleViewBundleIdPid(bundle_id) {
      this.tempBundId = bundle_id
      this.getBundleIdPidList(bundle_id);
      this.bundleIdPidDialogVisible = true;
    },
    getBundleIdPidList(bundle_id) {
      this.listLoading = true;
      bundleIdPidList({'bundle_id': bundle_id}).then(response => {
        this.bundleIdPidList = response.data.list;
        this.bundleIdPidTotal = response.data.total;
        this.listLoading = false;
      });
    },
    handleAddBundleIdPid() {
      this.bundleIdPidIsEdit = false;
      this.changeBundleIdPidDialogVisible = true;
      this.bundleIdPidFormData = Object.assign({}, defaultBundleIdPidFormData);
      this.bundleIdPidFormData.bundle_id = this.tempBundId
    },
    handleEditBundleIdPid(index, row) {
      this.bundleIdPidIsEdit = true;
      this.changeBundleIdPidDialogVisible = true;
      this.bundleIdPidFormData.id = row.id
      this.bundleIdPidFormData.bundle_id = row.bundle_id
      this.bundleIdPidFormData.pid = row.pid
      this.bundleIdPidFormData.sort = row.sort
    },
    handleSendBundleIdPidData(bundleIdPidForm) {
      this.$refs[bundleIdPidForm].validate((valid) => {
        if (valid) {
          this.$confirm('是否提交数据', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            if (!this.bundleIdPidIsEdit) {
              createBundleIdPid({ postData: this.bundleIdPidFormData }).then(response => {
                this.$refs[bundleIdPidForm].resetFields();
                this.$message({
                  message: '添加成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.bundleIdPidDialogVisible = false
              });
            } else if (this.bundleIdPidIsEdit) {
              updateBundleIdPid(this.bundleIdPidFormData.id, { postData: this.bundleIdPidFormData }).then(response => {
                this.$refs[bundleIdPidForm].resetFields();
                this.$message({
                  message: '修改成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.bundleIdPidDialogVisible = false
              });
            }

          });
        } else {
          this.$message({
            message: '验证失败',
            type: 'error',
            duration: 1000
          });
          return false;
        }
      });
    },
    handleDeleteBundleIdPid(id) {
      this.$confirm('是否要进行该删除操作?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteBundleIdPid(id).then(response => {
          this.$message({
            message: '删除成功！',
            type: 'success',
            duration: 1000
          });
          this.getBundleIdPidList(this.tempBundId);
        });
      })
    },
    changeVersionReviewStatus(row) {
      changeVersionReview(row.id, {postData: row}).then(response => {
        if (response.errorCode == 200) {
           this.$message({
              message: '修改状态成功',
              type: 'success',
              duration: 1000
            });
         }       
      });

    },
    handleResetSearch() {
      this.listQuery = Object.assign({}, defaultListQuery);
    },
    handleSearchList() {
      this.listQuery.cur_page = 1;
      this.getList();
    },
    handleDeleteVersionReview(index, row) {
      this.deleteVersionReview(row.id);
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
    getList() {
      this.listLoading = true;
      versionReviewList(this.listQuery).then(response => {
        this.listLoading = false;
        this.list = response.data.list;
        this.total = response.data.total;
      });
    },
    handleAddVersionReview() {
      this.isEdit = false;
      this.versionReviewDialogVisible = true;
      this.versionReviewFormData = Object.assign({}, defaultVersionReviewFormData);
    },
    handleEditVersionReview(index, row) {
      this.isEdit = true;
      this.versionReviewDialogVisible = true;
      this.versionReviewFormData.id = row.id
      this.versionReviewFormData.bundle_id = row.bundle_id
      this.versionReviewFormData.bundle_version = row.bundle_version
      this.versionReviewFormData.version_review_status = row.version_review_status
      this.versionReviewFormData.default_pid = row.default_pid
      this.versionReviewFormData.bundle_desc = row.bundle_desc
      this.versionReviewFormData.bundle_status = row.bundle_status
    },
    handleSendData(versionReviewForm) {
      this.$refs[versionReviewForm].validate((valid) => {
        if (valid) {
          this.$confirm('是否提交数据', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            if (!this.isEdit) {
              createVersionReview({ postData: this.versionReviewFormData }).then(response => {
                this.$refs[versionReviewForm].resetFields();
                this.$message({
                  message: '添加成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.versionReviewDialogVisible = false
              });
            } else if (this.isEdit) {
              updateVersionReview(this.versionReviewFormData.id, { postData: this.versionReviewFormData }).then(response => {
                this.$refs[versionReviewForm].resetFields();
                this.$message({
                  message: '修改成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.versionReviewDialogVisible = false
              });
            }

          });
        } else {
          this.$message({
            message: '验证失败',
            type: 'error',
            duration: 1000
          });
          return false;
        }
      });
    },
    deleteVersionReview(id) {
      this.$confirm('是否要进行该删除操作?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteVersionReview(id).then(response => {
          this.$message({
            message: '删除成功！',
            type: 'success',
            duration: 1000
          });
          this.getList();
        });
      })
    },
  }
}

</script>
<style scoped>
.input-width {
  width: 203px;
}

</style>
