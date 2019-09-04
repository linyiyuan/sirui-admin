import request from '@/utils/request'
export function menuTypeList(params) {
  return request({
    url:'/menu/menu_type',
    method:'get',
    params:params
  })
}

export function createMenuType(params) {
  return request({
    url:'/menu/menu_type',
    method:'post',
    data:params
  })
}

export function updateMenuType(id, params) {
  return request({
    url:'/menu/menu_type' + '/' + id,
    method:'put',
    data:params
  })
}

export function deleteMenuType(id) {
  return request({
    url:'/menu/menu_type' + '/' + id,
    method:'delete',
    data:id,
  })
}
