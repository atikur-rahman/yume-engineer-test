import type { EventHandler, EventHandlerRequest, H3Error } from "h3";
import {getQuery} from "#imports";

export const defineBackendRequestHandler = <
  T extends EventHandlerRequest,
  D,
>(requestConfig: {
  route: string;
  method: "GET" | "PUT" | "POST";
}): EventHandler<T, D> =>
  defineEventHandler<T>(async (event) => {
    try {
      const config = useRuntimeConfig(event);
      const query = getQuery(event)

      const data = await $fetch(requestConfig.route, {
        method: requestConfig.method,
        baseURL: config.api,
        headers: {
          Accept: "application/json",
          Authorization: event.context.auth,
        },
        query,
        onResponseError({ request, response }) {
          console.error(
            "[Backend request failure]",
            request,
            response.status,
            response.statusText
          );
        },
      });
      return { data };
    } catch (err) {
      return createError({
        status: 401,
      });
    }
  });
