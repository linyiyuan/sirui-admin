import request from '@/utils/request'
export function getMenuRecordByUid(params) {
  return request({
    url:'/menu/get_menu_record_by_uid',
    method:'get',
    params:params
  })
}
