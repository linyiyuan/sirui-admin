import Vue from 'vue'
import Router from 'vue-router'
import Layout from '../views/layout/Layout'

Vue.use(Router)

/**
 * hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
 *                                if not set alwaysShow, only more than one route under the children
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noredirect           if `redirect:noredirect` will no redirct in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar,
  }
 **/
export const constantRouterMap = [
  { path: '/login', component: () => import('@/views/login/index'), hidden: true },

  {
    path: '',
    component: Layout,
    redirect: '/home',
    children: [{
        path: 'home',
        name: 'home',
        component: () => import('@/views/home/index'),
        meta: { title: '首页', icon: 'home' }
      },
      { path: '/401', component: () => import('@/views/error/401'), hidden: true },
      { path: '/404', component: () => import('@/views/error/404'), hidden: true },

    ]
  },



]

export default new Router({
  // mode: 'history', //后端支持可开
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRouterMap
})


/**
 * 需要过滤动态加载的路由
 **/
export const asyncRouterMap = [{
    path: '/auth',
    component: Layout,
    name: 'Api:auth',
    alwaysShow: true,
    meta: { title: '用户模块', icon: 'user' },
    children: [{
        path: 'user',
        name: 'Api:auth/user-index',
        component: () => import('@/views/user/users/index'),
        meta: { title: '用户列表', icon: 'product-list' }
      },
      {
        path: 'user/create',
        name: 'Api:auth/user-store',
        component: () => import('@/views/user/users/create'),
        meta: { title: ' 添加用户', icon: 'product-add' },
        hidden: true
      },
      {
        path: 'user/update',
        name: 'Api:auth/user-update',
        component: () => import('@/views/user/users/update'),
        meta: { title: '修改用户', icon: 'product-add' },
        hidden: true
      },
      {
        path: 'role',
        name: 'Api:auth/role-index',
        component: () => import('@/views/user/role/index'),
        meta: { title: '角色列表', icon: 'role' },
      },
      {
        path: 'permission',
        name: 'Api:auth/permission-index',
        component: () => import('@/views/user/permission/index'),
        meta: { title: '权限管理', icon: 'permission' },
      },
    ]
  },
  {
    path: '/menu',
    component: Layout,
    name: 'Api:menu',
    redirect: '/menu/orderMenu',
    alwaysShow: true,
    meta: { title: '点餐系统', icon: 'menu' },
    children: [{
        path: 'orderMenu',
        name: 'Api:menu/orderMenu',
        component: () => import('@/views/menu/orderMenu'),
        meta: { title: '点餐平台', icon: 'menuing' }
      },
      {
        path: 'menuType',
        name: 'Api:menu/menuType',
        component: () => import('@/views/menu/menuType'),
        meta: { title: '菜品分类', icon: 'menuType' }
      },
      {
        path: 'restaurant',
        name: 'Api:menu/restaurant',
        component: () => import('@/views/menu/restaurant'),
        meta: { title: '餐馆管理', icon: 'restaurant' }
      },
      {
        path: 'menuManager',
        name: 'Api:menu/menuManager',
        component: () => import('@/views/menu/menuManager'),
        meta: { title: '菜品管理', icon: 'food and beverage' }
      },
      {
        path: 'menuConfig',
        name: 'Api:menu/menuConfig',
        component: () => import('@/views/menu/menuConfig'),
        meta: { title: '参数配置', icon: 'tools' }
      },
    ]
  },
  {
    path: '/game',
    component: Layout,
    name: 'Api:game',
    redirect: '/game/gameUser',
    alwaysShow: true,
    meta: { title: '游戏用户', icon: 'users' },
    children: [{
        path: 'gameUser',
        name: 'Api:game/gameUser',
        component: () => import('@/views/game/gameUser'),
        meta: { title: '游戏用户', icon: 'users' }
      },
      {
        path: 'rechargeRank',
        name: 'Api:game/rechargeRank',
        component: () => import('@/views/game/rechargeRank'),
        meta: { title: '充值排行', icon: 'rank' }
      },
    ]
  },
    {
    path: '/passport',
    component: Layout,
    name: 'Api:passport',
    redirect: '/passport/agent_matching',
    alwaysShow: true,
    meta: { title: '游戏中心', icon: 'passport' },
    children: [
      {
        path: 'agent_matching',
        name: 'Api:passport/agentMatching',
        component: () => import('@/views/passport/agentMatching'),
        meta: { title: '匹配信息', icon: 'agent_matching' }
      },
      {
        path: 'agent_matching_record',
        name: 'Api:passport/agentMatchingRecord',
        component: () => import('@/views/passport/agentMatchingRecord'),
        meta: { title: '匹配记录', icon: 'agent_matching_record' }
      },
    {
        path: 'version_review',
        name: 'Api:passport/versionRreview',
        component: () => import('@/views/passport/versionReview'),
        meta: { title: '审核开关', icon: 'switch' }
      },
    ]
  },
  {
    path: '/system',
    component: Layout,
    name: 'Api:system',
    redirect: '/system/getConfig',
    alwaysShow: true,
    meta: { title: '系统管理', icon: 'tools' },
    children: [{
        path: 'getConfig',
        name: 'Api:system/get_config-getConfig',
        component: () => import('@/views/system/index/'),
        meta: { title: '系统配置', icon: 'product-list' }
      },
      {
        path: 'getLog',
        name: 'Api:system/get_operate_log-getList',
        component: () => import('@/views/system/operateLog/'),
        meta: { title: '操作日志', icon: 'log' }
      },
    ]
  },

  { path: '*', redirect: '/404', hidden: true }

]
