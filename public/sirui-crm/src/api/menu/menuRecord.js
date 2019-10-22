import request from '@/utils/request'
export function getMenuRecordByUid(params) {
  return request({
    url:'/menu/get_menu_record_by_uid',
    method:'get',
    params:params
  })
}

export function getMenuRecordByType(params) {
  return request({
    url:'/menu/get_menu_record_by_type',
    method:'get',
    params:params
  })
}

export function orderMenu(params) {
  return request({
    url:'/menu/order_menu',
    method:'post',
    data:params
  })
}

export function removeMenuOrder(params) {
  return request({
    url:'/menu/remove_menu_order',
    method:'delete',
    data:params
  })
}

