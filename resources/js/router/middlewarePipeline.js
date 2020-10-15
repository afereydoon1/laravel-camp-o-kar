export default function middlewarePipeline(context, middleware, index) {
    let nextMiddleware = middleware[index];
    if (! nextMiddleware) {
        return context.next
    }

    let nextPipeline = () => middlewarePipeline(
        context, middleware, ++index
    );

    return nextMiddleware({ ...context, pipe: nextPipeline });
}
