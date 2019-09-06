import request from '@/utils/request'
export function menuConfigList(params) {
  return request({
    url:'/menu/menu_config',
    method:'get',
    params:params
  })
}

export function updateMenuConfig(id, params) {
  return request({
    url:'/menu/menu_config' + '/' + id,
    method:'put',
    data:params
  })
}