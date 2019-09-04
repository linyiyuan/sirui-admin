import request from '@/utils/request'
export function menuList(params) {
  return request({
    url:'/menu/menu',
    method:'get',
    params:params
  })
}

export function createMenu(params) {
  return request({
    url:'/menu/menu',
    method:'post',
    data:params
  })
}

export function updateMenu(id, params) {
  return request({
    url:'/menu/menu' + '/' + id,
    method:'put',
    data:params
  })
}

export function deleteMenu(id) {
  return request({
    url:'/menu/menu' + '/' + id,
    method:'delete',
    data:id,
  })
}
